<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Subject;

use Eki\NRW\Component\SPBase\Persistence\ValueObject;

/**
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class SubjectInfo extends ValueObject
{
	/**
	* Subject's' unique ID 
	* 
	* @var mixed
	*/
	public $id;
}
