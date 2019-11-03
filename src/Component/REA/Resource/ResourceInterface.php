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

use Eki\NRW\Mdl\REA\Resource\ResourceInterface as BaseResourceInterface;
use Eki\NRW\Common\Element\HasElementsInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface ResourceInterface extends
	BaseResourceInterface,
	HasElementsInterface
{
}
