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
use Eki\NRW\Component\Base\Persistence\Resourcing\Handler;

use Eki\NRW\Component\Base\Engine\ResourcingService as ResourcingServiceInterface;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Base\CoreExceptions\NotFoundException;

use Eki\NRW\Component\Resourcing\Resource\Type\ResourceTypeInterface;
use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;

use Eki\NRW\Component\REA\Resource\ResourceTypeBuilder;
use Eki\NRW\Component\REA\Resource\ResourceBuilder;

use Eki\NRW\Component\Core\Engine\Resourcing\TypeMeaningHelper;

/**
 * Resourcing Service
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class ResourcingService extends BaseService implements ResourcingServiceInterface
{
	/**
	* @var Handler
	*/
	protected $resourcingHandler;
	
	/**
	* @var ResourceTypeBuilder
	*/
	protected $resourceTypeBuilder;
	
	/**
	* @var ResourceBuilder
	*/
	protected $resourceBuilder;

	public function __construct(
		Engine $engine,
		array $settings,
		Handler $handler
	)
	{
		$this->resourcingHandler = $handler;		
		$this->resourceTypeBuilder = $this->engine->getSystemTools()->getTool('resourceTypeBuilder');
		$this->resourceBuilder = $this->engine->getSystemTools()->getTool('resourceBuilder');
		
		$this->typeMeaningHelper = new Resourcing\TypeMeaningHelper();

		parent::__construct($engine, $settings);
	}

	/**
	* @inheritdoc
	* 
	* @throws 
	*/
	public function createResourceType($identifier)
	{
        if (!$this->permissionResolver->canUser('resource_type', 'create', 
        	array(
        		'identifier' => $identifier
        	)
        )) 
        {
            throw new UnauthorizedException(
                'resource_type',
                'create',
                array(
                    'identifier' => $identifier
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$resourceType = $this->resourcingHandler->resourceTypeHandler()->create($identifier);
			$resourceType = $this->resourceTypeBuilder->createBuilder($identifier)
				->setConfigSubject($resourceType)
				->objectHasProperties()
				->objectHasOptions()
				->objectHasAttributes()
				->get()
			;
			
			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $resourceType;
	}

	/**
	* @inheritdoc
	*/	
	public function loadResourceType($resourceTypeId)
	{
        if (!$this->permissionResolver->canUser('resource_type', 'read', 
        	array(
        		'id' => $resourceTypeId
        	)
        )) 
        {
            throw new UnauthorizedException(
                'resource_type',
                'read',
                array(
        			'id' => $resourceTypeId
                )
            );
        }
		
		try
		{
			$resourceType = $this->resourcingHandler->resourceTypeHandler()->load($resourceTypeId);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Resource Type',
                array(
                    'id' => $resourceTypeId
                ),
                $e
            );
		}
		
		return $resourceType;
	}

	/**
	* @inheritdoc
	*/	
	public function loadResourceTypeByIdentifier($identifier)
	{
        if (!$this->permissionResolver->canUser('resource_type', 'read', 
        	array(
        		'identifier' => $identifier
        	)
        )) 
        {
            throw new UnauthorizedException(
                'resource_type',
                'read',
                array(
	        		'identifier' => $identifier
                )
            );
        }

		try
		{
			$resourceType = $this->resourcingHandler->resourceTypeHandler()
				->loadByIdentifier($identifier)
			;
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Resource Type',
                array(
                    'identifier' => $identifier
                ),
                $e
            );
		}
		
		return $resourceType;
	}

	/**
	* @inheritdoc
	*/
	public function deleteResourceType(ResourceTypeInterface $resourceType)
	{
        if (!$this->permissionResolver->canUser('resource_type', 'remove', $resourceType)) 
        {
            throw new UnauthorizedException(
                'resource_type',
                'remove',
                array(
                    'id' => $resourceType->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->resourcingHandler->resourceTypeHandler()->delete($resourceType);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
	}

	/**
	* @inheritdoc
	*/
	public function updateResourceType(ResourceTypeInterface $resourceType)
	{
        if (!$this->permissionResolver->canUser('resource_type', 'edit', $resourceType)) 
        {
            throw new UnauthorizedException(
                'resource_type',
                'edit',
                array(
                    'id' => $resourceType->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->resourcingHandler->resourceTypeHandler()->update($resourceType);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $resourceType;
	}

	/**
	* @inheritdoc
	*/
	public function createResource(ResourceTypeInterface $resourceType)
	{
        if (!$this->permissionResolver->canUser('resource', 'create', 
        	array(
        		'identifier' => $resourceType->getResourceType()
        	)
        )) 
        {
            throw new UnauthorizedException(
                'resource',
                'create',
                array(
        			'identifier' => $resourceType->getResourceType()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$resource = $this->resourceBuilder->createBuilder($identifier)
				->setConfigSubject(
					$this->resourcingHandler->resourceHandler()->create($resourceType->getResourceType())
				)
				->objectHasProperties()
				->objectHasOptions()
				->objectHasAttributes()
				->get()
			;

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $resource;
	}

	/**
	* @inheritdoc
	*/	
	public function loadResource($resourceId)
	{
        if (!$this->permissionResolver->canUser('resource', 'read', 
        	array(
        		'id' => $resourceId
        	)
        )) 
        {
            throw new UnauthorizedException(
                'resource',
                'read',
                array(
        			'id' => $resourceId
                )
            );
        }

		try
		{
			$resource = $this->resourcingHandler->resourceHandler()->load($resourceId);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Resource',
                array(
                    'id' => $resourceId
                ),
                $e
            );
		}
		
		return $resource;
	}

	/**
	* @inheritdoc
	*/
	public function deleteResource(ResourceInterface $resource)
	{
        if (!$this->permissionResolver->canUser('resource', 'remove', $resource)) 
        {
            throw new UnauthorizedException(
                'resource',
                'remove',
                array(
                    'id' => $resource->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->resourcingHandler->resourceHandler()->delete($resource);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
	}

	/**
	* @inheritdoc
	*/
	public function updateResource(ResourceInterface $resource)
	{
        if (!$this->permissionResolver->canUser('resource', 'edit', $resource)) 
        {
            throw new UnauthorizedException(
                'resource',
                'edit',
                array(
                    'id' => $resource->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->resourcingHandler->resourceHandler()->update($resource);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $resource;
	}

	/**
	* Make an linkage between two resources
	* 
	* @param ResourceInterface $resource
	* @param ResourceInterface $otherResource
	* @param string $linkageType
	* 
	* @return \Eki\NRW\Component\Networking\Resource\Relationship\LinkageInterface
	*/
	public function linkResources(ResourceInterface $resource, ResourceInterface $otherResource, $linkageType)
	{
        if (!$this->permissionResolver->canUser('resource', 'link', 
        	array(
        		'id' => $resource->getId(),
        		'other_id' => $otherResource->getId(),
        		'linkage_type' => $linkageType
        	)
        )) 
        {
            throw new UnauthorizedException(
                'resource',
                'link',
                array(
	        		'id' => $resource->getId(),
    	    		'other_id' => $otherResource->getId(),
        			'linkage_type' => $linkageType
                )
            );
        }

		$relatingService = $this->engine->getRelatingService();
		try 
		{
			$linkage = $relatingService->createRelation(
				'relationship', 
			    $this->typeMeaningHelper->setLinkageType($linkageType)->getType()
			);

			$linkage = $relatingService->linkRelationship($linkage, $resource, $otherResource);
		}
		catch(Execption $e)
		{
			throw $e;			
		}
		
		return $linkage;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getLinkage(ResourceInterface $resource, ResourceInterface $otherResource, $linkageType)
	{
		$linkages = $this->getLinkages($resource, $linkage, $otherResource);
		if (empty($linkages))
			return null;
		return reset($linkages);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getLinkages(ResourceInterface $resource, $linkageType = null, ResourceInterface $otherResource = null)
	{
		$type = $linkageType !== null ? $this->typeMeaningHelper->setLinkageType($linkageType)->getType() : null;
		$relationships = $this->engine->getRelatingService()->getRelations($resource, "relationship", $type);

		$linkages = [];
		foreach($relationships as $rl)
		{
			if ($otherResource === null or $rl->getOtherObject()->getId() === $otherResource->getId())
			{
				$linkages[] = $rl;
			}
		}
		
		return $linkages;
	}
}
