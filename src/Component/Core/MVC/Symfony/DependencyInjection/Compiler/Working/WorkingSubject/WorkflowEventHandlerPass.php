<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\DependencyInjection\Compiler\Working\WorkingSubject;

use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WorkflowEventHandlerPass implements CompilerPassInterface
{
	private $definitionTag;
	
	public function __construct($definitionTag)
	{
		$this->definitionTag = $definitionTag;
	}
	
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process( ContainerBuilder $container )
    {
    	$taggedServices = $container->findTaggedServiceIds($this->definitionTag);
		foreach ($taggedServices as $id => $tags) 
		{
			$definition = $container->findDefinition($id);
			$class = $definition->getClass();
			
			foreach($tags as $attributes)
			{
				$this->validateAttribute($id, 'workflow_name', $attributes);
			}
        }
	}
	
	private function validateAttribute($serviceId, $attrName, array $attributes)
	{
		if (!isset($attributes[$attrName]))
			throw new RuntimeException(sprintf(
				'The "%s" for the tag "%s" of service "%s" must be set.', 
				$attrName, $this->definitionTag, $serviceId
			));
	}
}