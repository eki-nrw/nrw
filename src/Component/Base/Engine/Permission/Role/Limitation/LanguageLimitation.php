<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 
namespace Eki\NRW\Component\Base\Engine\Permission\Role\Limitation;

use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation;

class LanguageLimitation extends Limitation
{
    /**
	* @inheritdoc
	* 
	*/
    public function getIdentifier()
    {
        return 'language';
    }
}
