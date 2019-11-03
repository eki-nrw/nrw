<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource;

use Eki\NRW\Component\REA\Resource\AbstractResource as BaseAbstractResource;
use Eki\NRW\Common\Res\Model\ResTrait;
use Eki\NRW\Common\Res\Model\TimestampableTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractResource extends BaseAbstractResource implements ResourceInterface
{
	use 
		ResTrait,
		TimestampableTrait
	;
}
