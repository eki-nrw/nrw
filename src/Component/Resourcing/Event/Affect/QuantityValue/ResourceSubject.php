<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Event\Affect\QuantityValue;

use Eki\NRW\Component\REA\Resource\ResourceInterface;
use Eki\NRW\Common\QuantityValue\QuantityValueSubjectInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
class ResourceSubject implements QuantityValueSubjectInterface
{
	/**
	* @var ResourceInterface
	*/	
	private $resource;
	
	public function __construct(ResourceInterface $resource)
	{
		$this->resource = $resource;
	}
	
	public function getSubject()
	{
		return $this->resource;
	}
}
