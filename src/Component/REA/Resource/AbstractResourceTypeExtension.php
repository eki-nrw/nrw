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

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractResourceTypeExtension implements ResourceTypeExtensionInterface
{
	/**
	* @inheritdoc
	*/
	public function support($subject, $position = null)
	{
		if ($subject instanceof ResourceTypeInterface)
			return true;
	}

	/**
	* @inheritdoc
	*/
	public function build(BuilderInterface $builder, array $contexts = [])
	{
		//...
	}
}
