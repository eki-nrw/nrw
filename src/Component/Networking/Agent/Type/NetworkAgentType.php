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

use Eki\NRW\Component\Networking\Agent\OrgAgentType;


/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class NetworkAgentType extends OrgAgentType
{
	/**
	* @inheritdoc
	*/
	public function getAgentType()
	{
		return 'network';		
	}
}
