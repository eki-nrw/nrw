<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Locating\Composite;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Composition extends Locating
{
	public function __construct($type = '', $name = '', $code = null)
	{
		parent::__construct(Constants::LOCATING_TYPE_COMPOSITION, $type, $name, $code);
	}
	
	public function compose($obj)
	{
		$this->addObj($obj);
	}

	public function decompose($obj)
	{
		$this->removeObj($obj);
	}
}
