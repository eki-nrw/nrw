<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject;


/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface AimInterface
{
	/**
	* Checks if the aim supports name and subject
	* 
	* @param string $name
	* @param mixed $subject
	* 
	* @return bool
	*/
	public function support($name, $subject);

	/**
	* Do main action
	* 
	* @param string $name
	* @param mixed $subject
	* @param array $contexts
	* 
	* @return array|null
	*/
	public function aim($name, $subject, array $contexts = []);
}
