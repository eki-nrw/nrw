<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine;

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\RelatingService as RelatingServiceInterface;
use Eki\NRW\Component\Base\Persistence\Handler as PersistenceHandler;
use Eki\NRW\Component\Base\Persistence\Relating\Handler;

use Eki\NRW\Component\Relating\Relation\RelationInterface;
use Eki\NRW\Component\Relating\Relation\RelationshipInterface;
use Eki\NRW\Component\Relating\Relation\GroupInterface;
use Eki\NRW\Component\Relating\ContextDiagram\Filter as ContextDiagramFilter;
use Eki\NRW\Component\Relating\ContextDiagram\Context as ContextDiagram;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentType;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentValue;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;

use Eki\NRW\Component\Core\Engine\Helper\PersistenceObjectLoader;
use Eki\NRW\Component\Core\Cache\Res\Cache as ResCache;

use Eki\NRW\Component\Core\Engine\Relating\Helper\RelationHelper;
use Eki\NRW\Component\Core\Engine\Relating\Helper\DomainMapper;

use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * Relating Service implementation.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class RelatingService extends MixedService implements RelatingServiceInterface
{
	/**
	* @var \Eki\NRW\Component\Base\Engine\Relating\Handler
	*/
	protected $relatingHandler;
	
	/**
	* @var \Eki\NRW\Component\Core\Engine\Relating\Helper\DomainMapper
	*/
	protected $domainMapper;
	
	/**
	* @var \Eki\NRW\Component\Core\Cache\Res\Cache
	*/
	private $resCache;
	
	/**
	* @var \Eki\NRW\Component\Core\Engine\Relating\Helper\RelationHelper;
	*/
	protected $relationHelper;
	
	public function __construct(
		Engine $engine,
		array $settings,
		PersistenceHandler $persistenceHandler
	)
	{
		$this->relatingHandler = $persistenceHandler->getRelatingHandler();
		
		$this->relationHelper = new RelationHelper();
		$this->domainMapper = new DomainMapper(
			$settings['factory'],
			new PersistenceObjectLoader($persistenceHandler)
		);
		
		$this->resCache = new ResCache(new ArrayAdapter(), "relating");
		
		parent::__construct($engine, $settings, $persistenceHandler);
	}
	
	/**
	* @inheritdoc
	*/
	public function createRelation($identifier, $type)
	{
        if (!is_string($identifier) || empty($identifier)) 
        {
            throw new InvalidArgumentValue('identifier', $identifier, 'Identififier must be not empty string.');
        }
        if (!empty($type) and !is_string($type)) 
        {
            throw new InvalidArgumentValue('type', $type, 'Type must be not empty string.');
        }
        
		if (!$this->permissionResolver->canUser(
			'relating', 
			'create', 
			array(
				'identifier' => $identifier, 
				'type' => $type
			)
		))
		{
            throw new UnauthorizedException('relation', 'create', 
            	array(
            		'identifier' => $identifier,
            		'type' => $type
            	)
            );
        }

        $this->beginTransaction();
        try 
        {
            $psRelation = $this->relatingHandler->createRelation($identifier, $type);
			$relation = $this->domainMapper->buildRelationDomainObject($psRelation);
			
            $this->commit();
        } 
        catch (Exception $e) 
        {
            $this->rollback();
            throw $e;
        }
        
        $this->resCache->set($relation);

        return $relation;
	}
		
	/**
	* @inheritdoc
	*/
	public function loadRelation($relationId)
	{
		try 
		{
			$psRelation = $this->relatingHandler->loadRelation($relationId);
		}
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Relating',
                array(
                    'id' => $relationId,
	        		'identifier' => $psRelation->identifier,
	        		'type' => $psRelation->type
                ),
                $e
            );
		}

        if (!$this->permissionResolver->canUser('relating', 'read', 
        	array(
        		'id' => $relationId,
        		'identifier' => $psRelation->identifier,
        		'type' => $psRelation->type
        	)
        )) 
        {
            throw new UnauthorizedException(
            	'relating', 
            	'read', 
            	array(
            		'id' => $relationId,
        			'identifier' => $psRelation->identifier,
        			'type' => $psRelation->type
            	)
            );
        }

		if (null !== ($relation = $this->resCache->get($relationId)))
			return $relation;

		try 
		{
			$relation = $this->domainMapper->buildRelationDomainObject($psRelation);

			$this->resCache->set($relation);
		}
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Relating',
                array(
                    'id' => $relationId,
	        		'identifier' => $psRelation->identifier,
	        		'type' => $psRelation->type
                ),
                $e
            );
		}
		
		return $relation;
	}
	
	/**
	* @inheritdoc
	*/
	public function deleteRelation(RelationInterface $relation)
	{
		if (!$this->permissionResolver->canUser('relating', 'remove', $relation))
		{
            throw new UnauthorizedException('relation', 'remove', 
            	array(
            		'id' => $relation->getId()
            	)
            );
        }

		if ($this->loadRelation($relation->getId()) ===  null)
		{
			throw new InvalidArgumentException("$relation", sprintf(
				"Relation with id %s cannot load, so cannot delete.", $relation->getId()
			));	
		}

		if (null !== $relation->getBase())
		{
			if (null !== $relation->getBase()->getObject())
				throw new InvalidArgumentException("relation", "Relation must not have base object before deletion.");
		}
		foreach($relation->getOthers() as $other)
		{
			if (null !== $other->getObject())
				throw new InvalidArgumentException("relation", "Relation must not have any other object before deletion.");
		}

        $this->beginTransaction();
        try 
        {
			$this->resCache->clear($relation);

        	$relationId = $relation->getId();
        	$psRelation = $this->relatingHandler->loadRelation($relationId);
            $this->relatingHandler->deleteRelation($psRelation);
            
            $this->commit();
        } 
        catch (\Exception $e) 
        {
            $this->rollback();
            throw $e;
        }
	}
	
	/**
	* @inheritdoc
	*/
	public function updateRelation(RelationInterface $relation)
	{
        if (!$this->permissionResolver->canUser('relating', 'edit', $relation)) 
        {
            throw new UnauthorizedException('relation', 'edit', array('id' => $relation->getId()));
        }

        $this->beginTransaction();
        try 
        {
        	$psRelation = $this->relatingHandler->loadRelation($relation->getId());
        	$psRelation = $this->domainMapper->buildRelationPersistObject($relation, $psRelation);
            $this->relatingHandler->updateRelation($psRelation);

			$this->resCache->set($relation);
            
            $this->commit();
        } 
        catch (Exception $e) 
        {
            $this->rollback();
            throw $e;
        }

		// TODO: $this->notificator->trigger();

        return $this->loadRelation($relation->getId());
	}

	/**
	* @inheritdoc
	*/
	public function canLinkRelationship(RelationshipInterface $relationship, $object, $otherObject)
	{
		//...
	}

	/**
	* @inheritdoc
	*/
	public function linkRelationship(RelationshipInterface $relationship, $object, $otherObject)
	{
        if (!is_object($object))
			throw new InvalidArgumentException("object", "Parameter 'object' is not object.");
        if (!is_object($otherObject))
			throw new InvalidArgumentException("otherObject", "Parameter 'otherObject' is not object.");

        if (!$this->permissionResolver->canUser('relating', 'link', $relationship, 
        	array('object' => $object, 'otherObject' => $otherObject)
        )) 
            throw new UnauthorizedException('relation', 'link', array( 'object' => $object, 'otherObject' => $otherObject ));

		if (null === $this->loadRelation($relationship->getId()))
            throw new NotFoundException('Relation', array(
            	'id' => $relationship->getId(),
                'identifier' => $relationship->getRelationType(),
                'type' => $relationship->getType()
            ));
            
		if ($relationship->getObject() !== null)
			throw new InvalidArgumentException("object", "Relationship has object. It must be unlinked by 'unlinkRelationship' method.");
		if ($relationship->getOtherObject() !== null)
			throw new InvalidArgumentException("otherObject", "Relationship has other objects. It must be unlinked by 'unlinkRelationship' method.");

		try 
		{
			$this->relationHelper->linkRelationship($relationship, $object, $otherObject);
			$this->updateRelation($relationship);
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException(
				"relationship", 
				array(
					'relationshipId' => $relationsip->getId(),
					'objectClass' => get_class($object) ,
					'otherObjectClass' => get_class($otherObject)
				),
				$e
			);
		}
		
		$this->notify(array(
			'subject' => $relationship,
			'position' => __METHOD__,
			'description' => "Link realtionship between objects",
			'action' => 'linkRelationship'
		));
		
		//$this->notificator->trigger();
		// In notificator:
		// + if object is agent, create role
		
		return $this->loadRelation($relationship->getId());
	}
	
	/**
	* @inheritdoc
	*/
	public function unlinkRelationship(RelationshipInterface $relationship)
	{
        if ($this->permissionResolver->canUser('relating', 'unlink', $relationship) !== true) 
            throw new UnauthorizedException('relation', 'unlink');
        
		if (null === $this->loadRelation($relationship->getId()))
            throw new NotFoundException('Relation', array(
            	'id' => $relationship->getId(),
                'identifier' => $relationship->getRelationType(),
                'type' => $relationship->getType()
            ));

        if ($relationship->getObject() === null)
			throw new InvalidArgumentException("relationship", "Relationship has no object to unlink.");
        if ($relationship->getOtherObject() === null)
			throw new InvalidArgumentException("relationship", "Relationship has no other object to unlink.");
        
        try 
        {
			$this->relationHelper->unlinkRelationship($relationship);        
			$this->updateRelation($relationship);
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException(
				"relationship", 
				array(
					'id' => $relationship->getId(),
					'objectClass' => get_class($object) ,
					'otherObjectClass' => get_class($otherObject)
				),
				$e
			);
		}

		// TODO: $this->notificator->trigger();
	}

	/**
	* @inheritdoc
	*/
	public function grouping(GroupInterface $group, $groupObject, array $otherObjects)
	{
        if (!$this->permissionResolver->canUser('relating', 'grouping', $group, 
        	array(
        		'groupObject' => $groupObject,
        		'otherObjects' => $otherObjects
        	)
        ))
            throw new UnauthorizedException(
            	'relation', 'grouping', 
            	array('id' => $group)
            );

		if (null === $this->loadRelation($group->getId()))
            throw new NotFoundException('Relation', array(
            	'id' => $group->getId(),
                'identifier' => $group->getRelationType(),
                'type' => $group->getType()
            ));

        if (!is_object($groupObject))
			throw new InvalidArgumentException("groupObject", "Parameter 'groupObject' is not object.");
		foreach($otherObjects as $otherObject)
		{
	        if (!is_object($otherObject))
				throw new InvalidArgumentException("otherObjects", "Parameter 'otherObjects' has one of object that is not object.");
		}

		try 
		{
			$this->relationHelper->grouping($group, $groupObject, $otherObjects);
			$this->updateRelation($group);
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException(
				"group",
				array(
					'id' => $group->getId(),
					'groupObjectClass' => get_class($groupObject)
				),
				$e
			);
		}

		return $this->loadRelation($group->getId());
	}
	
	/**
	* @inheritdoc
	*/
	public function ungrouping(GroupInterface $group)
	{
        if (!$this->permissionResolver->canUser('relating', 'ungrouping', $group,
        	array(
        		'groupObject' => $groupObject,
        		'otherObjects' => $otherObjects
        	)
        )) 
        {
            throw new UnauthorizedException('relation', 'ungrouping', array('id' => $group->getId()));
        }
		
		if (null === $this->loadRelation($group->getId()))
            throw new NotFoundException('Relation', array(
            	'id' => $group->getId(),
                'identifier' => $group->getRelationType(),
                'type' => $group->getType()
            ));

        if ($group->getObject() === null)
			throw new InvalidArgumentException("group", "Group has no object to ungroup.");
		foreach($group->getMembers() as $member)
		{
	        if ($member->getObject() === null)
				throw new InvalidArgumentException("group", "Group has not a member that is not object to unlink.");
		}
		
		try 
		{
			$this->relationHelper->ungrouping($group);
			$this->updateRelation($group);
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException(
				"group",
				array(
					'groupId' => $group->getId(),
					'groupObjectClass' => get_class($groupObject)
				),
				$e
			);
		}

		// TODO: $this->notificator->trigger();
	}

	/**
	* @inheritdoc
	*/
	public function addToGroup(GroupInterface $group, $object, $key = null)
	{
        if (!$this->permissionResolver->canUser('relating', 'add_to_group', $group, array($object))) 
        {
            throw new UnauthorizedException('relation', 'add_to_group', 
            	array('group_id' => $group->getId(), 'object_id' => $object->getId())
            );
        }
		
		try 
		{
			$this->relationHelper->addToGroup($group, $object, $key);
			$this->updateRelation($group);
		}
		catch(\Exception $e)
		{
            throw new InvalidArgumentException("object", "Invalid object to add", $e);
		}

		// TODO: $this->notificator->trigger();
	}

	/**
	* @inheritdoc
	*/
	public function removeFromGroup(GroupInterface $group, $object)
	{
        if (!$this->permissionResolver->canUser('relating', 'remove_from_group', $group, array($object))) 
        {
            throw new UnauthorizedException('relation', 'remove_from_group', 
            	array('group_id' => $group->getId(), 'object_id' => $object->getId())
            );
        }
		
		try 
		{
			$this->relationHelper->removeFromGroup($group, $object);
			$this->updateRelation($group);
		}
		catch(\Exception $e)
		{
            throw new InvalidArgumentException("object", "Invalid object to remove", $e);
		}

		// TODO: $this->notificator->trigger();
	}

	/**
	* @inheritdoc
	*/
	public function getRelations($baseObject, $identifier = null, $type = null, $offset = 0, $limit = 25, $all = null)
	{
        if (!$this->permissionResolver->canUser('relating', 'read', 
        	array(
        		'base' => true,
        		'identifier' => $identifier, 
        		'type' => $type
        	)
        )) 
            throw new UnauthorizedException('relation', 'read',
	        	array(
	        		'base' => true,
	        		'identifier' => $identifier, 
	        		'type' => $type
	        	)
            );

		$retRelations = [];
		if ($all === true)
			$offset = 0;
		while(true)
		{
			try
			{
				$psRelations = $this->relatingHandler->getRelations(
					array(
						'base' => true,
						'object' => $baseObject
					),
					$identifier, $type, 
					$offset, $limit
				);
				$relations = [];
				foreach($psRelations as $psRelation)
				{
					$relations[] = $this->domainMapper->buildRelationDomainObject($psRelation);
				}
			} 
			catch (BaseNotFoundException $e) 
			{
	            throw new NotFoundException(
	                'Relation',
	                array(
		        		'base' => true,
	                    'identifier' => $$identifier,
	                    'type' => $type
	                ),
	                $e
	            );
			}
			
			if ($all === true)
				$offset += $limit;

			$retRelations = $retRelations + $relations;
			
			if ($all !== true || empty($relations))
				break;
		}
		
		return $retRelations;
	}

	/**
	* @inheritdoc
	*/
	public function getRelation($baseObject, $identifier, $type)
	{
        if (!$this->permissionResolver->canUser('relating', 'read', 
        	array(
        		'base' => true,
        		'identifier' => $identifier, 
        		'type' => $type
        	)
        )) 
            throw new UnauthorizedException('relation', 'read',
	        	array(
	        		'base' => true,
	        		'identifier' => $identifier, 
	        		'type' => $type
	        	)
            );

		try
		{
			$psRelation = $this->relatingHandler->getRelation($baseObject, $identifier, $type);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Relation',
                array(
                	'base' => true,
                    'identifier' => $identifier,
                    'type' => $type
                ),
                $e
            );
		}
		
		return $relation;
	}

	/**
	* @inheritdoc
	*/
	public function getRelationOf($otherObject, $identifier, $type)
	{
        if (!$this->permissionResolver->canUser('relating', 'read', 
        	array(
        		'base' => false,
        		'identifier' => $identifier, 
        		'type' => $type
        	)
        )) 
            throw new UnauthorizedException('relation', 'read',
	        	array(
	        		'base' => false,
	        		'identifier' => $identifier, 
	        		'type' => $type
	        	)
            );

		try
		{
			$relation = $this->relatingHandler->getRelationOf($otherObject, $identifier, $type);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Relation',
                array(
                	'base' => false,
                    'identifier' => $identifier,
                    'type' => $typer
                ),
                $e
            );
		}
		
		return $relation;
	}

	/**
	* @inheritdoc
	*/
	public function getRelationsOf($otherObject, $identifier = null, $type = null, $offset = 0, $limit = 25)
	{
	    if (!$this->permissionResolver->canUser('relating', 'read', $identifier, $type)) 
            throw new UnauthorizedException('relation', 'read',
            	array(
            		'identifier' => $identifier,
            		'type' => $type
            	)
            );

		try
		{
			$psRelations = $this->relatingHandler->getRelations(
				array(
					'base' => false,
					'object' => $otherObject
				),
				$identifier, $type, 
				$offset, $limit
			);
			$relations = [];
			foreach($psRelations as $psRelation)
			{
				$relations[] = $this->domainMapper->buildRelationDomainObject($psRelation);
			}
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Relation',
                array(
                	'base' => false,
                    'identifier' => $$identifier,
                    'type' => $typer
                ),
                $e
            );
		}
		
		return $relations;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function formatNotification($subject)
	{
		$relation = $subject['subject'];
		/*
        return new RelationNotification(
        	$subject['subject'], 
        	isset($subject['description']) ? $subject['description'] : "", 
        	array(
        		'action' => $subject['action'],
        		'notification_position' => $subject['position'],
        		'identifier' => $relation->getRelationType(),
        		'type'=> $relation->getType(),
        		'domain_type' => $relation->getDomainType(),
        		'categorization_type' => $relation->getCategorizationType(),
        		'main_type' => $relation->getMainType(),
        		'sub_type' => $relation->getSubType(),
        	)
        );
        */
	}

	/**
	* @inheritdoc
	* 
	*/
	public function newContextDiagramFilter($central = null, $relationTypes = null, $entityClasses = null)
	{
		$filter = new ContextDiagramFilter()
			->setCentral($central)
			->setEntityClasses($entityClasses)
			->setRelationTypes(array_keys($relationTypes))
		;
		
		if ($relationTypes !== null)
		{
			foreach($relationTypes as $relationType => $type)
			{
				$filter->setTypesOfRelationType($relationType, $types);
			}
		}
		
		return $filter;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getContextDiagram(FilterInterface $contextDiagramFilter)
	{
		$filter = $contextDiagramFilter->getFilter();
		
		$allRelations = [];
		if (isset($filter['relations']))
		{
			foreach($filter['relations'] as $identifier => $types)
			{
				if ($types === null)
				{
					$relations = $this->getRelations($filter['central'], $identifier, null, 0, 25, true);
					$allRelations = $allRelations + $relations;
				}
				else
				{
					foreach($types as $type)
					{
						$relations = $this->getRelations($filter['central'], $identifier, $type, 0, 25, true);
						$allRelations = $allRelations + $relations;
					}
				}
			}
		}
		else
			$allRelations = $this->getRelations($filter['central'], null, null, 0, 25, true);
		
		return new ContextDiagram($filter['central'], $allRelations);
	}
}
