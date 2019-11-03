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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class WorkingDirectorRegistryPass implements CompilerPassInterface
{
	private $workingDirectorServiceId;
	private $definitionTag;
	
	public function __construct($workingDirectorServiceId, $definitionTag)
	{
		$this->workingDirectorServiceId = $workingDirectorServiceId;
		$this->definitionTag = $definitionTag;
	}
	
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process( ContainerBuilder $container )
    {
		if (!$container->has($this->workingDirectorServiceId)) 
            return;
    	
    	$definition = $container->findDefinition($this->workingDirectorServiceId);
    	
//    	$taggedServices = $container->findTaggedServiceIds('eki_nrw.working.director.working_subject');
    	$taggedServices = $container->findTaggedServiceIds($this->definitionTag);

		$registries = $definition->getArgument(0);
		foreach ($taggedServices as $id => $tags) 
		{
			if ($id === $this->workingDirectorServiceId)
				continue;

			foreach($tags as $attributes)
			{
				$this->validateAttribute($id, 'type', $attributes);
				$this->validateAttribute($id, 'workflow', $attributes);
				$this->validateAttribute($id, 'action', $attributes);

				$reg = array(
					'workflow' => $attributes['workflow'],
					'action' => $attributes['action'],
				);
				if (isset($attributes['working_subject']))
					$reg['working_subject'] = $attributes['working_subject'];
					
				$registries[$attributes['type']] = $reg;
			}

			//$container->removeDefinition($id);
        }

        $definition->replaceArgument(0, $registries);
	}
	
	private function validateAttribute($serviceId, $attrName, array $attributes)
	{
		if (!isset($attributes[$attrName]))
			throw new RuntimeException(sprintf('The "%s" for the tag "%s" of service "%s" must be set.', $attrName, $this->definitionTag, $serviceId));
	}
}