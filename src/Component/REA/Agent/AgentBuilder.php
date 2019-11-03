<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Agent;

use Eki\NRW\Common\Extension\AbstractBuilder;
use Eki\NRW\Common\Extension\CreateBuilderInterface;
use Eki\NRW\Common\Res\Factory\FactoryInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class AgentBuilder extends AbstractBuilder implements CreateBuilderInterface
{
	/**
	* @var AgentTypeInterface
	*/
	protected $agentType;
	
	/**
	* @inheritdoc
	*/
	public function __construct(AgentTypeInterface $agentType, array $extensions = [], FactoryInterface $factory = null)
	{
		$this->agentType = $agentType;
		
		parent::__construct($agentType->getAgentType(), $extensions, $factory);		
	}

	/**
	* @inheritdoc
	*/	
	protected function initialize($object)
	{
		parent::initialize($object);
		
		$object->setAgentType($this->agentType);
	}

	/**
	* @inheritdoc
	*/	
	protected function buildFromDependencyInjection($object)
	{
		$this->agentType->buildAgent($this, array('agent_object' => $object, 'position' => 'build'));
	}
	
	/**
	* @inheritdoc
	*/
	public function createBuilder($type)
	{
		if (!$type instanceof AgentTypeInterface)
			throw new \InvalidArgumentException(sprintf(
				"Parameter 'type' must be instance of %s. Given %s.",
				AgentTypeInterface::class,
				gettype($type)
			));
		
		$thisClass = get_class($this);
		
		return new $thisClass($type, array_values($this->getExtensions()), $this->factory);
	}	
}
