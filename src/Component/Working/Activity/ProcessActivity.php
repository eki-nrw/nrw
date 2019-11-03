<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Activity;

use Eki\NRW\Mdl\Working\ActivityTypeInterface;
use Eki\NRW\Common\REA\Processing\Process\HasProcessInterface;
use Eki\NRW\Common\REA\Processing\Process\HasProcessTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessActivity extends Activity implements ProcessActivityInterface
{
	use
		HasProcessTrait
	;

	/**
	* @inheritdoc
	*/
	protected function matchActivityType(ActivityTypeInterface $activityType)
	{
		parent::matchActivityType($activityType);
		
		if (!$activityType->is('process'))
			throw new \InvalidArgumentException("Process Activity must be process type.");
	}

	public function getProcessMethod()
	{
		if (null !== ($objectItem = $this->getObjectItem()))
		{
			$specifications = $objectItem->getSpecifications();	
			if (isset($specifications['process_method']))
				return $specifications['process_method'];
		}
	}
	
	public function setProcessMethod($method)
	{
		if (null !== ($objectItem = $this->getObjectItem()))
		{
			$specifications = $objectItem->getSpecifications();	
			$specifications['process_method'] = $method;
			$objectItem->setSpecifications($specifications);
		}
	}
}
