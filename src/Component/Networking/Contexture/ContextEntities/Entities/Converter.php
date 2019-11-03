<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Contexture\ContextEntities\Entities;

use Eki\NRW\Component\Contexture\ContextEntities\Entities\ConverterInterface;
use Eki\NRW\Mdl\REA\Relationship\TypeMeaningHelper as Helper;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
class Converter implements ConverterInterface
{
	/**
	* @var Helper
	*/
	private $helper;
	
	public function __construct($reaType)
	{
		$this->helper = new Helper();
		$this->helper->setReaType($reaType);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function toRelationshipType($scope, $level)
	{
		return $this->helper->setMainType($level)->getType();
	}
}
