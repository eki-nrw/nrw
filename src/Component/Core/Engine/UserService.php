<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine;

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\PermissionResolver;
use Eki\NRW\Component\Base\Persistence\Networking\Handler;
use Eki\NRW\Component\Base\Engine\UserService as UserServiceInterface;
use Eki\NRW\Component\Notification\NotificatorInterface;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\UnauthorizedException as BaseUnauthorizedException;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\Component\Core\Cache\Res\Metadata\Cache as ResCache;

use Eki\NRW\Component\SPBase\Persistence\Permission\User\Handler;

use Eki\NRW\Component\Base\Engine\Permission\User\User as BaseUser;
use Eki\NRW\Component\Base\Engine\Permission\User\Group as BaseGroup;

use Eki\NRW\Component\Core\Engine\Permission\User\User;
use Eki\NRW\Component\Core\Engine\Permission\User\Group;
use Eki\NRW\Component\Core\Engine\Permission\User\UserGroupAssignment;

use Symfony\Component\Cache\Adapter\ArrayAdapter;

use Exception;

/**
 * This service provides methods for managing users and user groups.
 *
 * @example Examples/user.php
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class UserService extends BaseService implements UserServiceInterface
{
	/**
	* @var ResCache
	*/
	private $resCache;
	
	/**
	* @var \Eki\NRW\Component\SPBase\Persistence\Permission\User\Handler
	*/
	protected $userHandler;

	public function __construct(
		Engine $engine,
		array $settings,
		Handler $handler
	)
	{
		$this->userHandler = $handler;		
		
		$this->resCache = new ResCache(
			new ArrayAdpter(), 
			new Metadata(
				"user", 
				array(
					'user' => User::class,
					"group" => Group::class,
					"assignment" => UserGroupAssignment:class,
 				),
				array(
					"cache_prefix" => "cache-user",
					"cache_tag" => "tag-user",
				)
			)
		);
		
		parent::__construct($engine, $settings);
	}

	/**
	* Load user group
	* 
	* @internal 
	* @throw \Eki\NRW\Component\Base\Exceptions\NotFoundException
	* 
	* @param mixed $groupId
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\User\Group
	*/	
	public function internalLoadGroup($groupId)
	{
		if (null !== ($group = $this->resCache->get($groupId)))
			return $group;
			
		$this->beginTransaction();
		try 
		{
			$psGroup = $this->userHandler->loadGroup($groupId);

			$this->commit();
		}
		catch(Exception $e)
		{
			$this->rollback();
			throw $e;			
		}
		
		$loadedGroup = $this->domainMapper->buildDomainUserObject($psGroup);	

		$this->resCache->set($loadedGroup);
		
		return $loadedGroup;
	}
	
    /**
	* @inheritdoc
	* 
	*/
    public function createGroup(BaseGroup $parentGroup = null)
    {
        if (!$this->permissionResolver->can('user', 'create_group', null, array($parentGroup))) 
            throw new UnauthorizedException('user', 'create_group');
    	
		$psParentGroup = null;
    	try 
    	{
    		if ($parentGroup !== null)
				$psParentGroup = $this->userHandler->loadGroup($parentGroup->getId());
		} 
		catch (\Exception $e) 
		{
			throw new NotFoundException("parentGroup", "Cannot find parent group.");
		}
    	
    	$this->beginTransaction();
    	try 
    	{
			$psGroup = $this->userHandler->createGroup($psParentGroup);
			$group = $this->domainMapper->buildDomainGroupObject($psGroup);
			
    		$this->commit();
		}
		catch(Exception $e)
		{
			$this->rollback();
			throw $e;
		}
		
		$this->updateGroup($group);
		
		return $group;
	}

    /**
	* @inheritdoc
	* 
	*/
    public function loadGroup($groupId)
    {
    	$loadedGroup = $this->internalLoadGroup($groupId);
    	
        if (!$this->permissionResolver->can('user', 'read_group', $loadedGroup)) 
            throw new UnauthorizedException('user', 'read_group');
		
		return $loadedGroup;
	}

    /**
	* @inheritdoc
	* 
	*/
    public function loadSubGroups(BaseGroup $group, $offset = 0, $limit = 25)
    {
		$psGroup = $this->userHandler->loadGroup($group->getId());
		$psSubGroups = $this->userHandler->getSubGroups($psGroup, $offset, $limit);
		
		$subGroups = [];
		foreach($psSubGroups as $psGr)
		{
			$subGroups[] = $this->loadGroup($psGr->getId());	
		}
		
		return $subGroups;
	}

    /**
	* @inheritdoc
	* 
	*/
    public function deleteGroup(BaseGroup $userGroup)
    {
        if (!$this->permissionResolver->can('user', 'remove_group', $userGroup)) 
        {
            throw new UnauthorizedException(
                'user',
                'remove_group',
                array(
                    'id' => $userGroup->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->userHandler->deleteGroup($userGroup);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}

		$this->resCache->clear($userGroup);
	}

    /**
	* @inheritdoc
	* 
	*/
    public function moveGroup(BaseGroup $userGroup, BaseGroup $newParent)
    {
        if (!$this->permissionResolver->can('user', 'move_group', $userGroup)) 
        {
            throw new UnauthorizedException(
                'user',
                'move_group',
                array(
                    'id' => $userGroup->getId()
                )
            );
        }
        
		$this->beginTransaction();
		try
		{ 
			$this->userHandler->moveGroup($userGroup, $newParent);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		$this->updateGroup($userGroup);
		$this->updateGroup($newParent);
	}

    /**
	* @inheritdoc
	* 
	*/
    public function updateGroup(Group $group)
	{
        if (!$this->permissionResolver->can('user', 'edit_group', $group)) 
        {
            throw new UnauthorizedException(
                'user',
                'edit_group',
                array(
                    'id' => $group->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$psGroup = $this->userHandler->loadGroup($group->getId());
			$psGroup = $this->domainMapper->updatePersistenceGroupObject($psGroup, $group);
			$this->userHandler->updateGroup($psGroup);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}

		$this->resCache->set($group);
		
		return $group;
	}    

    /**
	* @inheritdoc
	* 
	*/
    public function createUser(array $groups)
    {
        if (!$this->permissionResolver->can('user', 'create')) 
            throw new UnauthorizedException('user', 'create');

    	if (empty($groups))
    		throw new InvalidArgumentException("groups", "User must be child of one or more user group.");
    		
    	foreach($proups as $group)
    	{
			if (null === ($loadedGroup = $this->loadGroup($group->getId())))
				throw new InvalidArgumentException("groups", "One of group is not valid group.");
		}
    	
		$this->beginTransaction();
		try 
		{
			$psUser = $this->userHandler->createUser();
			foreach($groups as $group)
			{
				$psGroup = $this->userHandler->loadGroup($group->getId());
				$this->userHandler->assignUserToGroup($psUser, $psGroup);
			}

			$this->commit();
		}
		catch(Execption $e)
		{
			$this->rollback();
			throw $e;
		}

		$user = $this->domainMapper->buildDomainUserObject($psUser);
		$this->updateUser($user);
		
		return $user;
	}

    /**
	* Load user
	* 
	* @internal
	* @throw \Eki\NRW\Component\Base\Exceptions\NotFoundException
	* 
	* @param mixed $userId
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\User\User
	*/
    public function internalLoadUser($userId)
    {
		if (null !== ($user = $this->resCache->get($userId)))
			return $user;
			
		$this->beginTransaction();
		try 
		{
			$psUser = $this->userHandler->loadUser($userId);

			$this->commit();
		}
		catch(Exception $e)
		{
			$this->rollback();
			throw $e;			
		}
		
		$loadedUser = $this->domainMapper->buildDomainUserObject($psUser);	
		
		$this->resCache->set($loadedUser);
		
		return $loadedUser;
	}

    /**
	* @inheritdoc
	* 
	*/
    public function loadUser($userId)
    {
    	$loadedUser = $this->internalLoadUser($userId);
    	
        if (!$this->permissionResolver->can('user', 'read', $loadedUser)) 
            throw new UnauthorizedException('user', 'read');

		return $loadedUser;
	}

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
    public function loadUserByCredentials($login, $password)
    {
        if (!$this->permissionResolver->hasAccess('user', 'read')) 
            throw new UnauthorizedException('user', 'read');

		if (null !== ($user = $this->resCache->get($login."+".$password, "login+password")))
			return $user;
			
		$this->beginTransaction();
		try 
		{
			$psUser = $this->userHandler->loadUserByCredential($login, $password);

			$this->commit();
		}
		catch(Exception $e)
		{
			$this->rollback();
			throw $e;			
		}
		
		$loadedUser = $this->domainMapper->buildDomainUserObject($psUser);	
		
		$this->resCache->set($loadedUser, "login+password");
		
		return $loadedUser;
	}

    /**
	* @inheritdoc
	* 
	*/
    public function loadUserByLogin($login)
    {
        if (!is_string($login) || empty($login)) {
            throw new InvalidArgumentValue('login', $login);
        }

        if (!$this->permissionResolver->hasAccess('user', 'read')) 
            throw new UnauthorizedException('user', 'read');

		if (null !== ($user = $this->resCache->get($login, "login")))
			return $user;

		$this->beginTransaction();
		try 
		{
	        $psUser = $this->userHandler->loadUserByLogin($login);

			$this->commit();
		}
		catch(Exception $e)
		{
			$this->rollback();
			throw $e;			
		}

		$loadedUser = $this->domainMapper->buildDomainUserObject($psUser);	

		$this->resCache->set($loadedUser, "login");

        return $loadedUser;
	}

    /**
	* @inheritdoc
	* 
	*/
    public function loadUsersByEmail($email)
    {
        if (!$this->permissionResolver->hasAccess('user', 'read')) 
            throw new UnauthorizedException('user', 'read');

		if (null !== ($user = $this->resCache->get($email, "email")))
			return $user;

		$this->beginTransaction();
		try 
		{
	        $psUser = $this->userHandler->loadUserByEmail($email);

			$this->commit();
		}
		catch(Exception $e)
		{
			$this->rollback();
			throw $e;			
		}

		$loadedUser = $this->domainMapper->buildDomainUserObject($psUser);	

		$this->resCache->set($loadedUser, "email");

        return $loadedUser;
	}

    /**
	* @inheritdoc
	* 
	*/
    public function loadUserByToken($hash)
    {
        if (!is_string($hash) || empty($hash)) {
            throw new InvalidArgumentValue('hash', $hash);
        }

        $psUser = $this->userHandler->loadUserByToken($hash);
		$loadedUser = $this->domainMapper->buildDomainUserObject($psUser);	
        
        return $user;
    }

    /**
	* @inheritdoc
	* 
	*/
    public function deleteUser(BaseUser $user)
	{
        if (!$this->permissionResolver->can('user', 'remove', $user)) 
        {
            throw new UnauthorizedException(
                'user',
                'remove',
                array(
                    'id' => $user->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$psUser = $this->userHandler->loadUser($user->getId());
			$this->userHandler->deleteUser($psUser);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		$this->resCache->clear($user);
		$this->resCache->clear($user, "login");
		$this->resCache->clear($user, "login+password");
		$this->resCache->clear($user, "email");
	}    

    /**
	* @inheritdoc
	* 
	*/
    public function updateUser(BaseUser $user)
    {
        if (!$this->permissionResolver->can('user', 'edit', $user)) 
        {
            throw new UnauthorizedException(
                'user',
                'edit',
                array(
                    'id' => $user->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$psUser = $this->userHandler->loadUser($user->getId());
			$psUser = $this->domainMapper->updatePersistenceUserObject($psUser, $user);
			$this->userHandler->updateUser($psUser);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}

		$this->resCache->set($user);		
		$this->resCache->set($user, "login");		
		$this->resCache->set($user, "login+password");		
		$this->resCache->set($user, "email");		

		return $user;
	}

    /**
	* @inheritdoc
	* 
	*/
    public function assignUserToGroup(BaseUser $user, BaseGroup $userGroup)
    {
        if (!$this->permissionResolver->can('user', 'assign_group', $user, $userGroup)) 
        {
            throw new UnauthorizedException(
                'user',
                'assign_group',
                array(
                    'id' => $user->getId(),
                    'group_id' => $userGroup->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$psUser = $this->userHandler->loadUser($user->getId());
			$psGroup = $this->userHandler->loadGroup($userGroup->getId());
			$this->userHandler->assignUserToGroup($psUser, $psGroup);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
	}

    /**
	* @inheritdoc
	* 
	*/
    public function unassignUserFromGroup(BaseUser $user, BaseGroup $userGroup)
    {
        if (!$this->permissionResolver->can('user', 'unassign_group', $user, $userGroup)) 
        {
            throw new UnauthorizedException(
                'user',
                'unassign_group',
                array(
                    'id' => $user->getId(),
                    'group_id' => $userGroup->getId()
                )
            );
        }
        
		$this->beginTransaction();
		try
		{ 
			$psUser = $this->userHandler->loadUser($user->getId());
			$psGroup = $this->userHandler->loadGroup($userGroup->getId());
			$this->userHandler->unassignUserFromGroup($psUser, $psGroup);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
	}

    /**
	* @inheritdoc
	* 
	*/
    public function loadGroupsOfUser(BaseUser $user, $offset = 0, $limit = 25)
    {
    	$loadedUser = $this->loadUser($user->getId());
        if (!$this->permissionResolver->can('user', 'read', $loadedUser)) 
            throw new UnauthorizedException('user', 'read');

    	$groups = array();
		$this->beginTransaction();
		try
		{ 
			$psUser = $this->userHandler->loadUser($user->getId());

			$psGroups = $this->userHandler->getGroupsOfUser($psUser, $limit, $offset);
			foreach($psGroups as $psGroup)
			{
				$groups[] = $this->loadGroup($psGroup->getId());
			}

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $groups;
	}

    /**
	* @inheritdoc
	* 
	*/
    public function loadUsersOfGroup(BaseGroup $userGroup, $offset = 0, $limit = 25)
    {
		// ????!!!!!
	}
}
