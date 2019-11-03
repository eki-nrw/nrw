<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Bundle\CoreBundle\EventListener;

use Eki\NRW\Common\Configuration\VersatileScopeInterface;
use Eki\NRW\Component\Core\MVC\Symfony\Event\ScopeChangeEvent;
use Eki\NRW\Component\Core\MVC\Symfony\MVCEvents;
use Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccessAware;
use Eki\NRW\Mdl\MVC\Symfony\View\ViewManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ConfigScopeListener implements EventSubscriberInterface
{
    /**
     * @var \Eki\NRW\Common\Configuration\VersatileScopeInterface
     */
    private $configResolver;

    /**
     * @var \Eki\NRW\Mdl\MVC\Symfony\View\ViewManagerInterface|\Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccessAware
     */
    private $viewManager;

    /**
     * @var \Eki\NRW\Mdl\MVC\Symfony\View\ViewProvider|\Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccessAware
     */
    private $viewProviders;

    public function __construct(
        VersatileScopeInterface $configResolver,
        ViewManagerInterface $viewManager
    ) {
        $this->configResolver = $configResolver;
        $this->viewManager = $viewManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            MVCEvents::CONFIG_SCOPE_CHANGE => array('onConfigScopeChange', 100),
            MVCEvents::CONFIG_SCOPE_RESTORE => array('onConfigScopeChange', 100),
        );
    }

    public function onConfigScopeChange(ScopeChangeEvent $event)
    {
        $contextAccess = $event->getContextAccess();
        $this->configResolver->setDefaultScope($contextAccess->name);
        if ($this->viewManager instanceof ContextAccessAware) {
            $this->viewManager->setContextAccess($contextAccess);
        }

        foreach ($this->viewProviders as $viewProvider) {
            if ($viewProvider instanceof ContextAccessAware) {
                $viewProvider->setContextAccess($contextAccess);
            }
        }
    }

    /**
     * Sets the complete list of view providers.
     *
     * @param array $viewProviders
     */
    public function setViewProviders(array $viewProviders)
    {
        $this->viewProviders = $viewProviders;
    }
}
