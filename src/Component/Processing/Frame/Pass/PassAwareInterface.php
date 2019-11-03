<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Passing\Frame\Pass;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface PassAwareInterface
{
	/**
	* Sets/Resets pass
	* 
	* @param PassInterface|null $pass
	* 
	* @return void
	*/
	public function setPass(PassInterface $pass = null);
}
