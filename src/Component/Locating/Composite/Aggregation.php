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

use Eki\NRW\Common\Relations\Node;


/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Aggregation extends Locating
{
	public function __construct($type = '', $name = '', $code = null)
	{
		parent::__construct(Constants::LOCATING_TYPE_AGGREGATION, $type, $name, $code);
	}
	
	public function aggregate($obj)
	{
		$this->addObj($obj);
	}

	public function segregate($obj)
	{
		$this->removeObj($obj);
	}
}
