<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Tests\Helper;

use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
final class CommonHelper
{
	static public function createArrayCache()
	{
		return new ArrayAdapter();
	}
}

