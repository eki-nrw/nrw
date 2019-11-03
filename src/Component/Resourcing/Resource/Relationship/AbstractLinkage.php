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

use Eki\NRW\Mdl\REA\Resource\AbstractLinkage as BaseAbstractLinkage;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractLinkage extends BaseAbstractLinkage implements LinkageInterface
{
	use
		ResTrait
	;
}
