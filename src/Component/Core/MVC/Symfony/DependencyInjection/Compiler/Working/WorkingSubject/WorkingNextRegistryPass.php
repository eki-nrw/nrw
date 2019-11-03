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

class WorkingNextRegistryPass implements CompilerPassInterface
{
	private $workingDirectorServiceId;
	private $definitionTag;
	
	public function __construct($workingNextServiceId, $definitionTag)
	{
		$this->workingNextServiceId = $workingNextServiceId;
		$this->definitionTag = $definitionTag;
	}
	
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process( ContainerBuilder $container )
    {
		if (!$container->has($this->workingNextServiceId)) 
            return;
    	
    	$definition = $container->findDefinition($this->workingNextServiceId);
    	
//    	$taggedServices = $container->findTaggedServiceIds('eki_nrw.working.working_subject.next');
    	$taggedServices = $container->findTaggedServiceIds($this->definitionTag);

		$nextSubjetTypes = $definition->getArgument(0);
		foreach ($taggedServices as $id => $tags) 
		{
			if ($id === $this->workingNextServiceId)
				continue;

			foreach($tags as $attributes)
			{
				$this->validateAttribute($id, 'type', $attributes);
				$this->validateAttribute($id, 'next_subject', $attributes);
					
				$nextSubjetTypes[$attributes['type']] = $attributes['next_subject'];
			}

			//$container->removeDefinition($id);
        }

        $definition->replaceArgument(0, $nextSubjetTypes);
	}
	
	private function validateAttribute($serviceId, $attrName, array $attributes)
	{
		if (!isset($attributes[$attrName]))
			throw new RuntimeException(sprintf('The "%s" for the tag "%s" of service "%s" must be set.', $attrName, $this->definitionTag, $serviceId));
	}
}