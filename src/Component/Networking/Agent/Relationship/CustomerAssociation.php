<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Agent\Relationship;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class CustomerAssociation extends AbstractAssociation
{
	public function __construct($subType)
	{
		parent::__construct('customer', $subType);
	}
}
