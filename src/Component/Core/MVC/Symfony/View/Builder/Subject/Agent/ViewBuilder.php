<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\View\Builder\Subject\Agent;

use Eki\NRW\Mdl\MVC\Symfony\View\Builder\Subject\ViewBuilder as BaseViewBuilder;
use Eki\NRW\Mdl\MVC\Symfony\View\Configurator;
use Eki\NRW\Mdl\MVC\Symfony\View\ParametersInjector;

/**
 * Builds AgentView objects.
 */
class ViewBuilder extends BaseViewBuilder
{
    public function __construct(
        Configurator $viewConfigurator,
        ParametersInjector $viewParametersInjector,
        Loader $loader
    ) 
    {
        parent ::__construct($viewConfigurator, $viewParametersInjector, $loader, "agent");
    }
}
