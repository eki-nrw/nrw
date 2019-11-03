<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Agent;

use Eki\NRW\Mdl\REA\Agent\AbstractAgent as BaseAbstractAgent;
use Eki\NRW\Common\Element\HasElementsTrait;
use Eki\NRW\Common\Extension\BaseBuildSubjectTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractAgent extends BaseAbstractAgent implements AgentInterface
{
	use
		HasElementsTrait,
		BaseBuildSubjectTrait
	;
}
