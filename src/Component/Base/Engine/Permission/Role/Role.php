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

use Eki\NRW\Mdl\Permission\RoleInterface;
use Eki\NRW\Component\Base\Engine\Values\ValueObject;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class Role extends ValueObject implements RoleInterface
{
	use
		ResTrait
	;
	
    /**
     * @var string
     */
    protected $identifier;
}
