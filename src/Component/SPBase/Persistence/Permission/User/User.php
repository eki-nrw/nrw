<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence\Permission\User;

use Eki\NRW\Component\SPBase\Persistence\ValueObject;
use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Common\Res\Model\ResTrait;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class User extends ValueObject implements
	ResInterface
{
	use
		ResTrait
	;
	
    /**
     * User login.
     *
     * @var string
     */
    protected $login;

    /**
     * User E-Mail address.
     *
     * @var string
     */
    protected $email;

    /**
     * User password hash.
     *
     * @var string
     */
    protected $passwordHash;

    /**
     * Hash algorithm used to hash the password.
     *
     * @var int
     */
    protected $hashAlgorithm;

    /**
     * Flag to signal if user is enabled or not.
     *
     * User can not login if false
     *
     * @var bool
     */
    protected $enabled = false;
}
