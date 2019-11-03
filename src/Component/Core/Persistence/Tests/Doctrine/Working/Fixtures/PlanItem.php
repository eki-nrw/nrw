<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Tests\Doctrine\Working\Fixtures;

use Eki\NRW\Component\Working\PlanItem\PlanItem as BasePlanItem;

class PlanItem extends BasePlanItem
{
	public function setId($id)
	{
		$this->id = $id;
	}
}
