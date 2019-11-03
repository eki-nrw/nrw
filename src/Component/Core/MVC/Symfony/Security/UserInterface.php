<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Security;

use Eki\NRW\Component\Base\Permission\User\User as BaseUser;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Interface for Repository based users.
 */
interface UserInterface extends AdvancedUserInterface
{
    /**
     * @return \Eki\NRW\Component\Base\Engine\Permission\User\User
     */
    public function getBaseUser();

    /**
     * @deprecated Will be replaced by {@link ReferenceUserInterface::getAPIUser()}, adding LogicException to signature.
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\User $baseUser
     */
    public function setBaseUser(BaseUser $baseUser);
}
