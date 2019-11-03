<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\DependencyInjection\Compiler\Working\Builder;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SubjectPass implements CompilerPassInterface
{
	private $subjectDirectorServiceId;
	private $getSubjectTypeServiceId;
	
	public function __construct($subjectDirectorServiceId, $getSubjectTypeServiceId)
	{
		$this->subjectDirectorServiceId = $subjectDirectorServiceId;
		$this->getSubjectTypeServiceId = $getSubjectTypeServiceId;
	}
	
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process( ContainerBuilder $container )
    {
		if (!$container->has($this->subjectDirectorServiceId)) 
            return;
		if (!$container->has($this->getSubjectTypeServiceId)) 
            return;
    	
    	$directorDefinition = $container->findDefinition($this->subjectDirectorServiceId);
    	$getSubjecTypeDefinition = $container->findDefinition($this->getSubjectTypeServiceId);
    	
    	$taggedServices = $container->findTaggedServiceIds('eki_nrw.working.subject');

		$registries = $directorDefinition->getArgument(0);
		$getSubjecTypeCallbacks = $getSubjecTypeDefinition->getArgument(0);
		foreach ($taggedServices as $id => $tags) 
		{
			$subjectDefinition = $container->findDefinition($id);
			$subjectClass = $subjectDefinition->getClass();

			foreach($tags as $attributes)
			{
				$this->validateAttribute($id, 'type', $attributes);
//				$this->validateAttribute($id, 'object', $attributes);
				$this->validateAttribute($id, 'callback', $attributes);
				$this->validateAttribute($id, 'importor', $attributes);
				$this->validateAttribute($id, 'validator', $attributes);

				$this->validateAttribute($id, 'get_type', $attributes);
					
				$registries[] = array(
					'type' => $attributes['type'],
//					'object' => $attributes['object'],
					'object' => $subjectClass,
					'callback' => $attributes['callback'],
					'importor' => $attributes['importor'],
					'validator' => $attributes['validator']
				);
				
				$getSubjecTypeCallbacks[$subjectClass] = $attributes['get_type'];
			}
        }

        $directorDefinition->replaceArgument(0, $registries);
        $getSubjecTypeDefinition->replaceArgument(0, $getSubjecTypeCallbacks);
	}
	
	private function validateAttribute($serviceId, $attrName, array $attributes)
	{
		if (!isset($attributes[$attrName]))
			throw new \RuntimeException(sprintf("One of service tags '%s' has no attribute '%s'", $serviceId, $attrName));
	}
}