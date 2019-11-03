<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Permission\Role;

use Eki\NRW\Component\SPBase\Persistence\ValueObject;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
 */
abstract class Policy extends ValueObject implements ResInterface
{
	use
		ResTrait
	;
	
	/**
	* @var string
	*/	
	public $service;
	
	/**
	* @var string
	*/
	public $function;

    /**
     * Array of policy limitations, which is just a random hash map.
     *
     * The limitation array may look like:
     * <code>
     *  array(
     *      'Resource' => array(
     *          'abc',
     *          'production',
     *      ),
     *      'Foo' => array( 'Bar' ),
     *      …
     *  )
     * </code>
     *
     * Where the keys are the limitation identifiers, and the respective values
     * are an array of limitation values
     *
     * @var array|string If string, then only the value '*' is allowed, meaning all limitations.
     *                   Can not be a empty array as '*' should be used in this case.
     */
	public $limitations;

	/**
	* @var mixed
	*/
	public $roleId;
}
