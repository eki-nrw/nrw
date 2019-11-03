<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Locating;

use Eki\NRW\Common\Relations\TypeMeaningInterface;
use Eki\NRW\Common\Relations\TypeMeanignHelper as BaseTypeMeaningHelper;
use Eki\NRW\Component\Locating\Composite\Constants;

/**
 * TypeMeaningHelper for Locating
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
*/
class TypeMeaningHelper extends BaseTypeMeaningHelper
{
	public function __construct()
	{
		$this->setRelationDomain(Constants::LOCATING_DOMAIN);
	}
	
	protected function setLocatingType($locatingType)
	{
		$this->setRestType($locatingType, TypeMeaningInterface::INDEX_CATEGORIZATION_TYPE);
		
		return $this;		
	}
	
	public function locatingIsLocation()
	{
		return $this->setLocatingType(Constants::LOCATING_TYPE_LOCATION);
	}

	public function locatingIsAggregation()
	{
		return $this->setLocatingType(Constants::LOCATING_TYPE_AGGREGATION);
	}

	public function locatingIsComposition()
	{
		return $this->setLocatingType(Constants::LOCATING_TYPE_COMPOSITION);
	}
}
