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
use Eki\NRW\Component\Base\Engine\NetworkingService as NetworkingServiceInterface;

use Eki\NRW\Component\SPBase\Persistence\Networking\Handler;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\UnauthorizedException as BaseUnauthorizedException;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Eki\NRW\Component\Networking\Agent\Type\AgentTypeInterface;
use Eki\NRW\Component\Networking\Agent\AgentInterface;
use Eki\NRW\Component\REA\Agent\AgentTypeBuilder;
use Eki\NRW\Component\REA\Agent\AgentBuilder;

use Eki\NRW\Component\Core\Engine\Networking\TypeMeaningHelper;

use Exception;

/**
 * Networking Service implementation
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class NetworkingService extends BaseService implements NetworkingServiceInterface
{
	/**
	* @var Handler
	*/
	protected $networkingHandler;
	
	/**
	* @var AgentTypeBuilder
	*/
	protected $agentTypeBuilder;

	/**
	* @var AgentBuilder
	*/
	protected $agentBuilder;
	
	/**
	* @var TypeMeaningHelper
	*/
	private $typeMeaningHelper;

	public function __construct(
		Engine $engine,
		array $settings,
		Handler $handler,
		AgentTypeBuilder $agentTypeBuilder,
		AgentBuilder $agentBuilder
	)
	{
		$this->networkingHandler = $handler;		
		$this->agentTypeBuilder = $agentTypeBuilder;
		$this->agentBuilder = $agentBuilder;
		
		$this->typeMeaningHelper = new Networking\TypeMeaningHelper();

		parent::__construct($engine, $settings);
	}

	/**
	* @inheritdoc
	* 
	* @throws 
	*/
	public function createAgentType($identifier)
	{
        if (!$this->permissionResolver->canUser('agent_type', 'create', 
        	array(
        		'identifier' => $identifier
        	)
        )) 
        {
            throw new UnauthorizedException(
                'agent_type',
                'create',
                array(
                    'identifier' => $identifier
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$agentType = $this->networkingHandler->agentTypeHandler()->create($identifier);
			$agentType = $this->agentTypeBuilder->createBuilder($identifier)
				->setConfigSubject($agentType)
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
		
		return $agentType;
	}

	/**
	* @inheritdoc
	*/	
	public function loadAgentType($agentTypeId)
	{
        if (!$this->permissionResolver->canUser('agent_type', 'read', 
        	array(
        		'id' => $agentTypeId
        	)
        )) 
        {
            throw new UnauthorizedException(
                'agent_type',
                'read',
                array(
        			'id' => $agentTypeId
                )
            );
        }
		
		try
		{
			$agentType = $this->networkingHandler->agentTypeHandler()->load($agentTypeId);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Agent Type',
                array(
                    'id' => $agentTypeId
                ),
                $e
            );
		}
		
		return $agentType;
	}

	/**
	* @inheritdoc
	*/	
	public function loadAgentTypeByIdentifier($identifier)
	{
        if (!$this->permissionResolver->canUser('agent_type', 'read', 
        	array(
        		'identifier' => $identifier
        	)
        )) 
        {
            throw new UnauthorizedException(
                'agent_type',
                'read',
                array(
	        		'identifier' => $identifier
                )
            );
        }

		try
		{
			$agentType = $this->networkingHandler->agentTypeHandler()
				->loadByIdentifier($identifier)
			;
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Agent Type',
                array(
                    'identifier' => $identifier
                ),
                $e
            );
		}
		
		return $agentType;
	}

	/**
	* @inheritdoc
	*/
	public function deleteAgentType(AgentTypeInterface $agentType)
	{
        if (!$this->permissionResolver->canUser('agent_type', 'remove', $agentType)) 
        {
            throw new UnauthorizedException(
                'agent_type',
                'remove',
                array(
                    'id' => $agentType->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->networkingHandler->agentTypeHandler()->delete($agentType);

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
	public function updateAgentType(AgentTypeInterface $agentType)
	{
        if (!$this->permissionResolver->canUser('agent_type', 'edit', $agentType)) 
        {
            throw new UnauthorizedException(
                'agent_type',
                'edit',
                array(
                    'id' => $agentType->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->networkingHandler->agentTypeHandler()->update($agentType);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $agentType;
	}

	/**
	* @inheritdoc
	*/
	public function createAgent(AgentTypeInterface $agentType)
	{
        if (!$this->permissionResolver->canUser('agent', 'create', 
        	array(
        		'identifier' => $agentType->getAgentType()
        	)
        )) 
        {
            throw new UnauthorizedException(
                'agent',
                'create',
                array(
        			'identifier' => $agentType->getAgentType()
                )
            );
        }
        
        try 
        {
	        $loadedAgentType = $this->loadAgentType($agentType->getId());
		}
		catch(\Exception $e)
		{
			throw new InvalidArgumentException("agentType", "Agent Type invalid.", $e);
		}

		$this->beginTransaction();
		try
		{ 
			$agent = $this->networkingHandler->agentHandler()->create($agentType->getAgentType());
			$agent = $this->agentBuilder->createBuilder($agentType)
				->setConfigSubject($agent)
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
		
		return $agent;
	}

	/**
	* @inheritdoc
	*/	
	public function loadAgent($agentId)
	{
        if (!$this->permissionResolver->canUser('agent', 'read', 
        	array(
        		'id' => $agentId
        	)
        )) 
        {
            throw new UnauthorizedException(
                'agent',
                'read',
                array(
        			'id' => $agentId
                )
            );
        }

		try
		{
			$agent = $this->networkingHandler->agentHandler()->load($agentId);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Agent',
                array(
                    'id' => $agentId
                ),
                $e
            );
		}
		
		return $agent;
	}

	/**
	* @inheritdoc
	*/
	public function deleteAgent(AgentInterface $agent)
	{
        if (!$this->permissionResolver->canUser('agent', 'remove', $agent)) 
        {
            throw new UnauthorizedException(
                'agent',
                'remove',
                array(
                    'id' => $agent->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->networkingHandler->agentHandler()->delete($agent);

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
	public function updateAgent(AgentInterface $agent)
	{
        if (!$this->permissionResolver->canUser('agent', 'edit', $agent)) 
        {
            throw new UnauthorizedException(
                'agent',
                'edit',
                array(
                    'id' => $agent->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->networkingHandler->agentHandler()->update($agent);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $agent;
	}

	/**
	* Make an association between two agents
	* 
	* @param AgentInterface $agent
	* @param AgentInterface $otherAgent
	* @param string $associationType
	* 
	* @return \Eki\NRW\Component\Networking\Agent\Relationship\AssociationInterface
	*/
	public function associateAgents(AgentInterface $agent, AgentInterface $otherAgent, $associationType)
	{
        if (!$this->permissionResolver->canUser('agent', 'associate', 
        	array(
        		'id' => $agent->getId(),
        		'other_id' => $otherAgent->getId(),
        		'association_type' => $associationType
        	)
        )) 
        {
            throw new UnauthorizedException(
                'agent',
                'associate',
                array(
	        		'id' => $agent->getId(),
    	    		'other_id' => $otherAgent->getId(),
        			'association_type' => $associationType
                )
            );
        }

		$relatingService = $this->engine->getRelatingService();
		try 
		{
			$association = $relatingService->createRelation(
				'relationship', 
			    $this->typeMeaningHelper->setAssociationType($associationType)->getType()
			);

			$association = $relatingService->linkRelationship($association, $agent, $otherAgent);
		}
		catch(Exeception $e)
		{
			throw $e;			
		}
		
		return $association;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getAssociation(AgentInterface $agent, AgentInterface $otherAgent, $associationType)
	{
		$associations = $this->getAssociations($agent, $association, $otherAgent);
		if (empty($associations))
			return null;
		return reset($associations);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getAssociations(AgentInterface $agent, $associationType = null, AgentInterface $otherAgent = null)
	{
		$type = $associationType !== null ? $this->typeMeaningHelper->setAssociationType($associationType)->getType() : null;
		$relationships = $this->engine->getRelatingService()->getRelations($agent, "relationship", $type);

		$associations = [];
		foreach($relationships as $rl)
		{
			if ($otherAgent === null or $rl->getOtherObject()->getId() === $otherAgent->getId())
			{
				$associations[] = $rl;
			}
		}
		
		return $associations;
	}
}
