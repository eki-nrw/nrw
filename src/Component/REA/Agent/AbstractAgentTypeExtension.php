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

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractAgentTypeExtension implements AgentTypeExtensionInterface
{
	/**
	* @inheritdoc
	*/
	public function support($subject, $position = null)
	{
		if ($subject instanceof AgentTypeInterface)
			return true;
	}
}
