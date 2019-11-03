<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Bundle\EkiNrwCoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('eki_nrw_core');

        $rootNode
            ->children()
				->arrayNode('system')
					->append($this->addPersistenceSection())
				->end()
			->end()
		;

		$this->addSystemSection($rootNode);
		
		$this->addResourcingSection($rootNode);
		$this->addWorkingSection($rootNode);

        return $treeBuilder;
    }

    private function addPersistenceSection()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('persistence');
        
        $node
        	->arrayNode('metadata')
        		->arrayPrototype()
        			->children
        				->arrayNode('classes')
        				->end()
        				->arrayNode('parameters')
        					->prototype()->end()
        				->end()
        			->end()
        		->end()
        	->end()
        ;
        
        return $node;
	}
    
    private function addWorkingSection(ArrayNodeDefinition $node)
    {
    	$node
    		->children()
				->arrayNode('working')
					->children()
						->array('subjects')
							->prototype('array')
								->children()
									->scalarNode('type')->end()
									->scalarNode('class')->end()
									->scalarNode('callback')->end()
									->arrayNode('importors')
										->children()
											->prototype('scalar')->end()
										->end()
									->end()
									->scalarNode('validator')->end()
								->end()
							->end()
						->end()
						->arrayNode('working_subject')
							->children()
								->arrayNode('types')
									->prototype('array')
										->children()
											->arrayNode('actions')
												->prototype('scalar')->end()
											->end()
											->arrayNode('workflow')
												->children()
													->arrayNode('places')
														->prototype('scalar')->end()
													->end()
													->arrayNode('transitions')
														->children()
															->prototype('array')
																->children()
																	->arrayNode('from')
																		->prototype('scalar')->end()
																	->end()
																	->arrayNode('to')
																		->prototype('scalar')->end()
																	->end()
																->end()
															->end()
														->end()
													->end()
												->end()
											->end()
										->end()
									->end()
								->end()
								->arrayNode('working')
									->children()
										->prototype('array')
											
										->end()
									->end()
								->end()
							->end()
						->end()
					->end()
				->end()
			->end()
		;
	}
	
    private function addResourcingSection(ArrayNodeDefinition $node)
    {
    	$node
    		->children()
    			->arrayNode('resource')
    				->children()
    					->arrayNode('methods')
    						->children()
    							->arrayNode('input')
    								->prototype('scalar')->end()
    							->end()
    							->arrayNode('output')
    								->prototype('scalar')->end()
    							->end()
    						->end()
    					->end()
    				->end()
    			->end()
    		->end()
    	;
	}
	
}
