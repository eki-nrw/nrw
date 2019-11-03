<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Permission\Role;

use Eki\NRW\Mdl\Permission\PolicyInterface;
use Eki\NRW\Component\Base\Engine\Values\ValueObject;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class Policy extends ValueObject implements PolicyInterface
{
	use
		ResTrait
	;

	/**
	* @var string
	*/	
	protected $service;

	/**
	* @var string
	*/	
	protected $function;
	
	/**
	* @inheritdoc
	* 
	*/
	public function getService()
	{
		return $this->service;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getFunction()
	{
		return $this->function;		
	}
}
