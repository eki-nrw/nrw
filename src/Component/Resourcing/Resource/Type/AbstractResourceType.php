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

use Eki\NRW\Mdl\REA\Resource\AbstractResourceType as BaseAbstractResourceType;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractResourceType extends BaseAbstractResourceType implements ResourceTypeInterface
{
	use
		ResTrait
	;
}
