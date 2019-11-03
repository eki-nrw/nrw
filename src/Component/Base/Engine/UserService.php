<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

use Eki\NRW\Component\Base\Engine\Permission\User\User;
use Eki\NRW\Component\Base\Engine\Permission\User\Group;

/**
 * This service provides methods for managing users and user groups.
 *
 * @example Examples/user.php
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface UserService
{
    /**
     * Creates a new user group.
     *
     * @param \Eki\NRW\Component\Base\Permission\User\Group|null $parentGroup Null if top user group
     *
     * @return \Eki\NRW\Component\Base\Permission\User\Group
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to create a user group
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if the input structure has invalid data
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\ContentFieldValidationException if a field in the $userGroupCreateStruct is not valid
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\ContentValidationException if a required field is missing or set to an empty value
     */
    public function createGroup(Group $parentGroup = null);

    /**
     * Loads a user group for the given id.
     *
     * @param mixed $groupId
     *
     * @return \Eki\NRW\Component\Base\Permission\User\Group
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to create a user group
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException if the user group with the given id was not found
     */
    public function loadGroup($groupId);

    /**
     * Loads the sub groups of a user group.
     *
     * @param \Eki\NRW\Component\Base\Permission\User\Group $userGroup
     * @param int $offset the start offset for paging
     * @param int $limit the number of user groups returned
     *
     * @return \Eki\NRW\Component\Base\Permission\User\Group[]
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to read the user group
     */
    public function loadSubGroups(Group $userGroup, $offset = 0, $limit = 25);

    /**
     * Removes a user group.
     *
     * the users which are not assigned to other groups will be deleted.
     *
     * @param \Eki\NRW\Component\Base\Permission\User\Group $userGroup
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to create a user group
     *
     * @return mixed[] Affected Location Id's (List of Locations of the Content that was deleted)
     */
    public function deleteGroup(Group $userGroup);

    /**
     * Moves the user group to another parent.
     *
     * @param \Eki\NRW\Component\Base\Permission\User\Group $userGroup
     * @param \Eki\NRW\Component\Base\Permission\User\Group $newParent
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to move the user group
     */
    public function moveGroup(Group $userGroup, Group $newParent);

    /**
     * Updates the group profile with fields and meta data.
     *
     * @param \Eki\NRW\Component\Base\Permission\User\Group $userGroup
     * @param \Eki\NRW\Component\Base\Permission\User\GroupUpdateStruct $userGroupUpdateStruct
     *
     * @return \Eki\NRW\Component\Base\Permission\User\Group
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to move the user group
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\ContentFieldValidationException if a field in the $userGroupUpdateStruct is not valid
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\ContentValidationException if a required field is set empty
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if a field value is not accepted by the field type
     */
    public function updateGroup(Group $userGroup);

    /**
     * Create a new user.
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\Group[] $groups the groups of type {@link \Eki\NRW\Component\Base\Permission\User\Group} which are assigned to the user after creation
     *
     * @return \Eki\NRW\Component\Base\Permission\User\User
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to move the user group
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\ContentFieldValidationException if a field in the $userCreateStruct is not valid
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\ContentValidationException if a required field is missing or set  to an empty value
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if a field value is not accepted by the field type
     *                                                                        if a user with provided login already exists
     */
    public function createUser(array $groups);

    /**
     * Loads a user.
     *
     * @param mixed $userId
     *
     * @return \Eki\NRW\Component\Base\Permission\User\User
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException if a user with the given id was not found
     */
    public function loadUser($userId);

    /**
     * Loads a user for the given login and password.
     *
     * @param string $login
     * @param string $password the plain password
     *
     * @return \Eki\NRW\Component\Base\Permission\User\User
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if credentials are invalid
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException if a user with the given credentials was not found
     */
    public function loadUserByCredentials($login, $password);

    /**
     * Loads a user for the given login.
     *
     * @param string $login
     *
     * @return \Eki\NRW\Component\Base\Permission\User\User
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException if a user with the given credentials was not found
     */
    public function loadUserByLogin($login);

    /**
     * Loads a user for the given email.
     *
     * Note: This method loads user by $email where $email might be case-insensitive on certain storage engines!
     *
     * Returns an array of Users since Eki NRW has under certain circumstances allowed
     * several users having same email in the past (by means of a configuration option).
     *
     * @param string $email
     *
     * @return \Eki\NRW\Component\Base\Permission\User\User[]
     */
    public function loadUsersByEmail($email);

    /**
     * Loads a user with user hash key.
     *
     * @param string $hash
     *
     * @return \Eki\NRW\Component\Base\Engine\Permission\User\User
     */
    public function loadUserByToken($hash);

    /**
     * This method deletes a user.
     *
     * @param \Eki\NRW\Component\Base\Permission\User\User $user
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to delete the user
     *
     * @return mixed[] Affected Location Id's (List of Locations of the Content that was deleted)
     */
    public function deleteUser(User $user);

    /**
     * Updates a user.
     *
     *
     * @param \Eki\NRW\Component\Base\Permission\User\User $user
     * @param \Eki\NRW\Component\Base\Permission\User\UserUpdateStruct $userUpdateStruct
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to update the user
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\ContentFieldValidationException if a field in the $userUpdateStruct is not valid
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\ContentValidationException if a required field is set empty
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if a field value is not accepted by the field type
     *
     * @return \Eki\NRW\Component\Base\Permission\User\User
     */
    public function updateUser(User $user);

    /**
     * Assigns a new user group to the user.
     *
     * If the user is already in the given user group this method does nothing.
     *
     * @param \Eki\NRW\Component\Base\Permission\User\User $user
     * @param \Eki\NRW\Component\Base\Permission\User\Group $userGroup
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to assign the user group to the user
     */
    public function assignUserToGroup(User $user, Group $userGroup);

    /**
     * Removes a user group from the user.
     *
     * @param \Eki\NRW\Component\Base\Permission\User\User $user
     * @param \Eki\NRW\Component\Base\Permission\User\Group $userGroup
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to remove the user group from the user
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if the user is not in the given user group
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\BadStateException If $userGroup is the last assigned user group
     */
    public function unassignUserFromGroup(User $user, Group $userGroup);

    /**
     * Loads the user groups the user belongs to.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed read the user or user group
     *
     * @param \Eki\NRW\Component\Base\Permission\User\User $user
     * @param int $offset the start offset for paging
     * @param int $limit the number of user groups returned
     *
     * @return \Eki\NRW\Component\Base\Permission\User\Group[]
     */
    public function loadGroupsOfUser(User $user, $offset = 0, $limit = 25);

    /**
     * Loads the users of a user group.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\UnauthorizedException if the authenticated user is not allowed to read the users or user group
     *
     * @param \Eki\NRW\Component\Base\Permission\User\Group $userGroup
     * @param int $offset the start offset for paging
     * @param int $limit the number of users returned
     *
     * @return \Eki\NRW\Component\Base\Permission\User\User[]
     */
    public function loadUsersOfGroup(Group $userGroup, $offset = 0, $limit = 25);
}
