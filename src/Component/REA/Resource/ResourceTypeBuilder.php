<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Resource;

use Eki\NRW\Common\Extension\BuilderInterface;
use Eki\NRW\Common\Extension\AbstractBuilder;
use Eki\NRW\Common\Extension\CreateBuilderInterface;
use Eki\NRW\Common\Res\Factory\FactoryInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ResourceTypeBuilder extends AbstractBuilder implements CreateBuilderInterface
{
	/**
	* @inheritdoc
	*/
	public function createBuilder($type)
	{
		$thisClass = get_class($this);
		
		return new $thisClass($type, $this->getFactory(), array_values($this->getExtensions()));
	}	
}
