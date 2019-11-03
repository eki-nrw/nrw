<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Type;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ComponentResourceType extends ProductResourceType
{
	/**
	* @inheritdoc
	*/
	public function getResourceType()
	{
		return 'product.component';		
	}
}
