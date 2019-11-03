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
class ContainerLinkage extends AbstractLinkage implements Contained
{
	public function __construct($label = 'container')
	{
		parent::__construct('container', '', $label);
	}
	
	public function getContainer()
	{
		return $this->getResource();
	}

	public function getContained()
	{
		return $this->getOtherResource();
	}
}
