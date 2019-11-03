<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Resource\Affection;

use Eki\NRW\Common\Affection\SubjectableInterface;
use Eki\NRW\Component\REA\Resource\ResourceInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
class Subjectable implements SubjectableInterface
{
	/**
	* @var ResourceInterface
	*/
	protected $resource;

	public function __construct(ResourceInterface $resource)
	{
		$this->resource = $resource;
	}
	
	public function getSubject()
	{
		return $this->resource;
	}
}
