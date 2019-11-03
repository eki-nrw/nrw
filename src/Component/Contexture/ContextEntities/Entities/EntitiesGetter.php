<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Contexture\ContextEntities\Entities;

use Eki\NRW\Mdl\Contexture\ContextEntities\Entities\EntitiesGetterInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
class EntitiesGetter implements EntitiesGetterInterface
{
	/**
	* @var RelationshipsGetterInterface
	*/
	protected $relationshipsGetter;
	
	/**
	* @var ConverterInterface
	*/
	protected $converter;

	public function __construct(RelationshipsGetterInterface $getter, ConverterInterface $converter)
	{
		$this->relationshipsGetter = $getter;
		$this->converter = $converter;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getEntities($boundary, $scope, $level)
	{
		$relationships = $this->relationshipsGetter->get(
			$boundary, 
			$this->converter->toRelationshipType($scope, $level)
		);	
		
		$entities = array();
		foreach($relationships as $relationship)
		{
			$entities[] = $relationship->getOtherObject();
		}
		
		return $entities;
	}
}
