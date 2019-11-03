<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Relationship;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Underlying
{
	public function getMain();
	public function getUnderlying();
}
