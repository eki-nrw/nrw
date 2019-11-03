<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Type;

use Eki\NRW\Component\REA\Agent\AbstractAgentType as BaseAbstractAgentType;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractAgentType extends BaseAbstractAgentType implements AgentTypeInterface
{
	use
		ResTrait
	;

	/**
	* @inheritdoc
	* 
	*/
	public function is($thing)
	{
		return false;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function accept($thing, $content)
	{
		return false;
	}
}
