<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Bundle\EkiNrwCoreBundle\Console;

use Eki\NRW\Mdl\Contexture\ContextAccess\ContextAccess;
use Symfony\Bundle\FrameworkBundle\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Eki-NRW console application.
 * Adds options specific to an Eki NRW environment, such as the contextaccess to use.
 */
class Application extends BaseApplication
{
    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);
        $this->getDefinition()->addOption(
            new InputOption('--contextaccess', null, InputOption::VALUE_OPTIONAL, 'ContextAccess to use for operations. If not provided, default context will be used')
        );
    }
}
