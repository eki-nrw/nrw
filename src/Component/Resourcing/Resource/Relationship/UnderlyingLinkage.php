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
class UnderlyingLinkage extends AbstractLinkage implements Underlying
{
	public function __construct($label = 'underlying')
	{
		parent::__construct('underlying', '', $label);
	}

	public function getMain()
	{
		return $this->getResource();
	}
	
	public function getUnderlying()
	{
		return $this->getOtherResource();
	}
}
