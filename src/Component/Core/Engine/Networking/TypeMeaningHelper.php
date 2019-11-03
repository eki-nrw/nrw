<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Networking;

use Eki\NRW\Common\Relations\TypeMeaningInterface;
use Eki\NRW\Mdl\REA\Relationship\TypeMeaningHelper as BaseTypeMeaningHelper;
use Eki\NRW\Mdl\REA\Relationship\Constants;

/**
 * TypeMeaningHelper for Networking
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
*/
class TypeMeaningHelper extends BaseTypeMeaningHelper
{
	public function __construct()
	{
		$this->setCategorizationType(Constants::REA_RELATIONSHIP_ASSOCIATION);
	}
	
	public function setAssociationType($associationType)
	{
		$this->setRestType($associationType, TypeMeaningInterface::INDEX_MAIN_TYPE);
		
		return $this;		
	}
	
	public function getAssociationType()
	{
		return $this->getRestType(TypeMeaningInterface::INDEX_MAIN_TYPE);
	}
}
