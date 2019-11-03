<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Base\Container\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This compiler pass will register eZ Publish field types.
 */
class RegisterLimitationTypePass implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @throws \LogicException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('eki_nrw.base.engine.factory')) {
            return;
        }

        $engineFactoryDef = $container->getDefinition('eki_nrw.base.engine.factory');

        // Limitation types.
        // Alias attribute is the limitation type name.
        foreach ($container->findTaggedServiceIds('eki_nrw.limitationType') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['alias'])) {
                    throw new \LogicException('eki_nrw.limitationType service tag needs an "alias" attribute to identify the limitation type. None given.');
                }

                $engineFactoryDef->addMethodCall(
                    'registerLimitationType',
                    array(
                        $attribute['alias'],
                        new Reference($id),
                    )
                );
            }
        }
    }
}
