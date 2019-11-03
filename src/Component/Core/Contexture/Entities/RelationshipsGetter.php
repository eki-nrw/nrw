<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Contexture\Entities;

use Eki\NRW\Component\Contexture\Entities\RelationshipsGetterInterface;
use Eki\NRW\Component\Base\Engine\RelatingService;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
class RelationshipsGetter implements RelationshipsGetterInterface
{
	/**
	* @var RelatingService
	*/
	private $relatingService;

	public function __construct(RelatingService $relatingService)
	{
		$this->relatingService = $relatingService;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getAll($boundary)
	{
		$relationships = $this->relatingService->getRelations($boundary, "relationship");	
		
		return $relationships;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function get($boundary, $relationshipType)
	{
		$relationships = $this->relatingService->getRelations($boundary, "relationship", $relationshipType);	
		
		return $relationships;
	}
}
