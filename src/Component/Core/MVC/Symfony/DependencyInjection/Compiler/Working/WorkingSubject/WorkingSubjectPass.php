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

class WorkingSubjectPass implements CompilerPassInterface
{
	private $workingSubjectDirectorServiceId;
	
	public function __construct($workingSubjectDirectorServiceId)
	{
		$this->workingSubjectDirectorServiceId = $workingSubjectDirectorServiceId;
	}
	
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process( ContainerBuilder $container )
    {
		if (!$container->has($this->workingSubjectDirectorServiceId)) 
            return;
    	
    	$directorDefinition = $container->findDefinition($this->workingSubjectDirectorServiceId);
    	
    	$taggedServices = $container->findTaggedServiceIds('eki_nrw.working_subject');

		$registries = $directorDefinition->getArgument(0);
		foreach ($taggedServices as $id => $tags) 
		{
			$workingSubjectDefinition = $container->findDefinition($id);
			$workingSubjectClass = $workingSubjectDefinition->getClass();

			foreach($tags as $attributes)
			{
				$this->validateAttribute($id, 'type', $attributes);
				$this->validateAttribute($id, 'workflow_handler', $attributes);
				$this->validateAttribute($id, 'action_handler', $attributes);
					
				$registries[$attributes['type']] = array(
					'type' => $attributes['type'],
					'working_subject' => $workingSubjectClass,
					'workflow_handler' => $attributes['workflow_handler'],
					'action_handler' => $attributes['action_handler'],
				);
			}
        }

        $directorDefinition->replaceArgument(0, $registries);
	}
	
	private function validateAttribute($serviceId, $attrName, array $attributes)
	{
		if (!isset($attributes[$attrName]))
			throw new \RuntimeException(sprintf("One of service tags '%s' has no attribute '%s'", $serviceId, $attrName));
	}
}