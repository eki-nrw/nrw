<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Processing;

use Eki\NRW\Common\Relations\TypeMeaningInterface;
use Eki\NRW\Common\Relations\TypeMeaningHelper as BaseTypeMeaningHelper;

/**
 * TypeMeaningHelper for Processing
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
*/
class TypeMeaningHelper extends BaseTypeMeaningHelper
{
	public function __construct()
	{
		$this->setCategorizationType("Processing");
	}
	
	public function setProcessingType($processingType)
	{
		$this->setCategorizationType($processingType);
		
		return $this;		
	}

	public function setContinuation($continuation)
	{
		$this->setMainType($continuation);
		
		return $this;		
	}
}
