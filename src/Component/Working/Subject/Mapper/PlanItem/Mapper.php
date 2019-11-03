<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Mapper;

use Eki\NRW\Mdl\Working\Subject\AbstractMapper;
use Eki\NRW\Common\Compose\ObjectItem\ObjectItemInterface;
use Eki\NRW\Component\Working\Plan\PlanInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class Mapper extends AbstractMapper
{
	protected function mapObjectItem($type, $data)
	{
		if ($data instanceof PlanInterface)
		{
			$objectItem = $data->getObjectItem();
		}
		else if ($data instanceof ObjectItemInterface)
			$objectItem = $data;
		
		$planItem = $this->getSubject();
		
		$newObjectItem = new ObjectItem()
			->setItem($objectItem->getItem()),
			->setSpecifications($objectItem->getSpecifications())
			->setLink($objectItem->getLink())
		;
		
		$planItem->setObjectItem($newObjectItem);
	}

	protected function mapSupportObjectItem($type, $data)
	{
		return 
			( $data instanceof PlanInterface 
			  and 
			  $data->getPlanType() === 'planitem.recipe')
			)
			or
			$data instanceof ObjectItemSourceInterface
		;
	}
}
