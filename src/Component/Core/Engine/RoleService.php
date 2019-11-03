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

use Eki\NRW\Component\Base\Engine\RoleService as RoleServiceInterface;

use Eki\NRW\Component\Base\Engine\Permission\Role\Role as BaseRole;
use Eki\NRW\Component\Base\Engine\Permission\Role\Policy as BasePolicy;
use Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment as BaseRoleAssignment;
use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation as BaseLimitation;
use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation\RoleLimitation as BaseRoleLimitation;

use Eki\NRW\Component\Core\Engine\Permission\Role\Role;
use Eki\NRW\Component\Core\Engine\Permission\Role\Policy;
use Eki\NRW\Component\Core\Engine\Permission\Role\Limitation;

use Eki\NRW\Component\Base\Persistence\Permission\User\User as BaseUser;
use Eki\NRW\Component\Base\Persistence\Permission\User\Group as BaseUserGroup;

use Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException as BaseNotFoundException;

use Eki\NRW\Component\Core\Base\Exceptions\BadStateException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentValue;
use Eki\NRW\Component\Core\Base\Exceptions\LimitationValidationException;
use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\NotFound\LimitationNotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;

use Eki\NRW\Component\Core\Engine\Permission\LimitationService;
use Eki\NRW\Component\Core\Engine\Permission\RoleDomainMapper;

use Eki\NRW\Component\SPBase\Persistence\Permission\Handler as PermissionHandler;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Handler as RoleHandler;
use Eki\NRW\Component\SPBase\Persistence\Permission\User\Handler as UserHandler;
use Eki\NRW\Component\SPBase\Persistence\Permission\Role\RoleAssignment as PSRoleAssignment;

use Eki\NRW\Component\Core\Engine\Notification\Role\RoleNotification;

use Eki\NRW\Component\Core\Cache\Res\Metadata\Registry\Cache as ResCache;

use Exception;

/**
 * Role Service implementation.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class RoleService extends BaseService implements RoleServiceInterface
{
	/**
	* @var Eki\NRW\Component\SPBase\Persistence\Permission\Handler
	*/
	protected $permissionHandler;

	/**
	* @var Eki\NRW\Component\SPBase\Persistence\Permission\Role\Handler
	*/
	protected $roleHandler;

	/**
	* @var Eki\NRW\Component\SPBase\Persistence\Permission\User\Handler
	*/
	protected $userHandler;

    /**
     * @var \Eki\NRW\Component\Core\Engine\Helper\LimitationService
     */
    protected $limitationService;
    
    /**
	* @var \Eki\NRW\Component\Core\Engine\Permission\RoleDomainMapper
	*/
    protected $domainMapper;
	
	public function __construct(
		Engine $engine,
		array $settings,
		PermissionHandler $handler
	)
	{
		$this->permissionHandler = $handler;
		$this->roleHandler = $handler->roleHandler();
		$this->userHandler = $handler->userHandler();
		
		$this->limitationService = new LimitationService($settings['policyMap']);
		$this->domainMapper = new RoleDomainMapper($this->limitationService);
		
		parent::__construct($engine, $settings);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function createRole($identifier, array $policies = [])
	{
        if (!is_string($identifier) || empty($identifier)) 
            throw new InvalidArgumentValue('identifier', $identifier, 'Identififier must be not-empty string.');

		foreach($policies as $policy)
		{
			if (!$policy instanceof BasePolicy)
	            throw new InvalidArgumentValue(
	            	'policies', 
	            	$policies, 
	            	sprintf("One of policies is not %s.", BasePolicy::class)
	            );
		}

		if ($this->permissionResolver->hasAccess('role', 'create') !== true)
            throw new UnauthorizedException('role', 'create');

        try 
        {
            $existingRole = $this->loadRoleByIdentifier($identifier);

            throw new InvalidArgumentException(
                '$identifier',
                "Role '{$existingRole->id}' with the specified identifier '{$identifier}' " .
                "already exists"
            );
        } 
        catch (BaseNotFoundException $e) 
        {
            // Do nothing
        }

        $this->beginTransaction();
        try 
        {
        	$psRole = $this->roleHandler->createRole($identifier);
            $role = $this->domainMapper->buildDomainRoleObject($psRole);
            
            foreach($policies as $policy)
            {
				$this->addPolicy($role, $policy);
			}

            $this->updateRole($role);
        
            $this->commit();
        } 
        catch (Exception $e) 
        {
            $this->rollback();
            throw $e;
        }
        
        return $this->loadRole($psRole->getId());
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function loadRole($roleId)
	{
        if ($this->permissionResolver->hasAccess('role', 'read') !== true) 
            throw new UnauthorizedException('role', 'read');

		try
		{
			$psRole = $this->roleHandler->loadRole($roleId);
			$role = $this->domainMapper->buildDomainRoleObject($psRole);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Role',
                array(
                    'id' => $roleId
                ),
                $e
            );
		}
		
		return $role;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function loadRoleByIdentifier($identifier)
    {
        if (!is_string($identifier)) 
            throw new InvalidArgumentValue('identifier', $identifier);

        if ($this->permissionResolver->hasAccess('role', 'read')) 
            throw new UnauthorizedException('role', 'read');

		try
		{
	        $psRole = $this->roleHandler->loadRoleByIdentifier($identifier);
			$role = $this->domainMapper->buildDomainRoleObject($psRole);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Role',
                array(
                    'identifier' => $identifier
                ),
                $e
            );
		}

        return $role;
    }

	public function updateRole(BaseRole $role)
	{
        if ($this->permissionResolver->canUser('role', 'update', $role)) 
            throw new UnauthorizedException('role', 'update');

        $this->beginTransaction();
        try 
        {
        	$psRole = $this->roleHandler->loadRole($role->getId());
        	$psRole = $this->domainMapper->updatePersistenceRoleObject($psRole, $role);
            $this->roleHandler->updateRole($psRole);

            $this->commit();
        } 
        catch (Exception $e) 
        {
            $this->rollback();
            throw $e;
        }
	}
	
	public function deleteRole(BaseRole $role)
	{
		if ($this->permissionResolver->canUser('role', 'remove', $role))
            throw new UnauthorizedException('role', 'remove');

        $this->beginTransaction();
        try 
        {
            $this->roleHandler->deleteRole($role->getId());
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
	public function loadPolicy($policyId)
	{
        if ($this->permissionResolver->hasAccess('role', 'read_policy') !== true) 
            throw new UnauthorizedException('role', 'read_policy');

		try
		{
			$psPolicy = $this->roleHandler->loadPolicy($policyId);
			$policy = $this->domainMapper->buildDomainPolicyObject($psPolicy);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Policy',
                array(
                    'id' => $policyId
                ),
                $e
            );
		}

		return $policy;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function addPolicy(BaseRole $role, BasePolicy $policy)
	{
		if (null === $this->loadRole($role->getId()))
			throw new InvalidArgumentException("role", "Role must be persisted.")
		
		if (null !== $policy->getId())
			throw new InvalidArgumentException("policy", "Policy must be arguemnts only.");

        if ($policy->getRole() !== null)
			throw new InvalidArgumentException("policy", "Policy is added to a certain role.");
		
        if ($this->permissionResolver->canUser('role', 'update', $role)) 
        {
            throw new UnauthorizedException(
            	'role', 'update', 
            	array(
            		'id' => $role->getId(),
            		'identifier' => $role->identifier;
            	)
            );
        }

        $limitationValidationErrors = $this->validatePolicy(
            $policy->getService(),
            $policy->getFunction(),
            $policy->getLimitations()
        );
        if (!empty($limitationValidationErrors)) {
            throw new LimitationValidationException($limitationValidationErrors);
        }

        $this->beginTransaction();
        try 
        {
        	$psPolicy = $this->roleHandler->createPolicy($policy);
            $psPolicy = $this->roleHandler->addPolicy($role->getId(), $psPolicy);
            
            $this->commit();
        } 
        catch (Exception $e) 
        {
            $this->rollback();
            throw $e;
        }

        return $this->loadPolicy($psPolicy->getId());
	}

	public function updatePolicy(BasePolicy $policy)
	{
        $role = $policy->getRole();
        if ($this->permissionResolver->canUser('role', 'update', $role, $policy)) 
            throw new UnauthorizedException(
            	'role', 'update',
            	array(
            		'id' => $policy->getId()
            	)
            );


        $this->beginTransaction();
        try 
        {
        	$psPolicy = $this->roleHandler->loadPolicy($policy->getId());
        	$psPolicy = $this->domainMapper->updatePersistencePolicyObject($psPolicy, $policy);
            $this->roleHandler->updatePolicy($psPolicy);

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
	public function removePolicy(BasePolicy $policy)
	{
        $role = $policy->getRole();
		if (null === ($role = $policy->getRole()))
            throw new InvalidArgumentValue("policy, ", "Role of the policy invalid.");

        if ($this->permissionResolver->canUser('role', 'update', $role, $policy)) 
        {
            throw new UnauthorizedException(
            	'role', 'update', 
            	array(
            		'id' => $role->getId(),
            		'identifier' => $role->identifier;
            	)
            );
        }
			
		$psPolicy = $this->roleHandler->loadPolicy($policy->getId());
		$this->roleHandler->deletePolicy($psPolicy);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function assignRoleToUser(BaseRole $role, BaseUser $user, RoleLimitation $roleLimitation = null)
	{
        if ($this->permissionResolver->canUser('role', 'assign', $user, $role) !== true) 
            throw new UnauthorizedException('role', 'assign');

        if ($roleLimitation === null) 
        {
            $limitation = null;
        } 
        else 
        {
            $limitationValidationErrors = $this->limitationService->validateLimitation($roleLimitation);
            if (!empty($limitationValidationErrors)) {
                throw new LimitationValidationException($limitationValidationErrors);
            }

            $limitation = array($roleLimitation->getIdentifier() => $roleLimitation->limitationValues);
        }

		$psUser = $this->userHandler->loadUser($user->getId());
		$psRole = $this->roleHandler->loadRole($role->getId());

        $limitation = $this->checkAssignmentAndFilterLimitationValues($psUser->getId(), $psRole, $limitation);

        $this->beginTransaction();
        try 
        {
            $this->permissionHandler->assignRoleToUser(
                $user->getId(),
                $role->getId(),
                $limitation
            );
            
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
	public function unassignRoleFromUser(BaseRole $role, BaseUser $user)
    {
        if ($this->permissionResolver->canUser('role', 'assign', $user, $role) !== true) 
        {
            throw new UnauthorizedException('role', 'assign');
        }

        $roleAssignments = $this->roleHandler->loadRoleAssignmentsByGroupId($user->getId());
        $isAssigned = false;
        foreach ($roleAssignments as $roleAssignment) {
            if ($roleAssignment->getRole()->getId() === $role->getId()) 
            {
                $isAssigned = true;
                break;
            }
        }

        if (!$isAssigned) {
            throw new InvalidArgumentException(
                '$user',
                'Role is not assigned to the given User'
            );
        }

        $this->beginTransaction();
        try 
        {
            $this->permissionHandler->unassignRoleFromUser($user->getId(), $role->getId());
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
	public function assignRoleToUserGroup(BaseRole $role, BaseUserGroup $userGroup)
	{
        if ($this->permissionResolver->canUser('role', 'assign', $userGroup, $role) !== true) 
        {
            throw new UnauthorizedException('role', 'assign');
        }

        if ($roleLimitation === null) {
            $limitation = null;
        } else {
            $limitationValidationErrors = $this->limitationService->validateLimitation($roleLimitation);
            if (!empty($limitationValidationErrors)) {
                throw new LimitationValidationException($limitationValidationErrors);
            }

            $limitation = array($roleLimitation->getIdentifier() => $roleLimitation->limitationValues);
        }

		$psUserGroup = $this->userHandler->loadUser($userGroup->getId());
		$psRole = $this->roleHandler->loadRole($role->getId());

        $limitation = $this->checkAssignmentAndFilterLimitationValues($userGroup->getId(), $psRole, $limitation);

        $this->beginTransaction();
        try 
        {
            $this->permissionHandler->assignRoleToUserGroup(
                $psUserGroup->getId(),
                $psRole->getId(),
                $limitation
            );
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
	public function unassignRoleFromUserGroup(BaseRole $role, BaseUserGroup $userGroup)
	{
        if ($this->permissionResolver->canUser('role', 'assign', $userGroup, $role) !== true) 
        {
            throw new UnauthorizedException('role', 'assign');
        }

        $roleAssignments = $this->roleHandler->loadRoleAssignmentsByGroupId($userGroup->getId());
        $isAssigned = false;
        foreach ($roleAssignments as $roleAssignment) 
        {
            if ($roleAssignment->getRole()->getId() === $role->getId()) 
            {
                $isAssigned = true;
                break;
            }
        }

        if (!$isAssigned) {
            throw new InvalidArgumentException(
                '$userGroup',
                'Role is not assigned to the given UserGroup'
            );
        }

        $this->beginTransaction();
        try {
            $this->permissionHandler->unassignRoleFromUserGroup($userGroup->getId(), $role->getId());
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function loadRoleAssignment($roleAssignmentId)
	{
        $psRoleAssignment = $this->roleHandler->loadRoleAssignment($roleAssignmentId);
        $role = $this->loadRole($psRoleAssignment->roleId);

        if ($this->permissionResolver->canUser('role', 'read', $role) !== true) 
        {
            throw new UnauthorizedException('role', 'read');
        }
        
        try 
        {
        	$roleAssignment = $this->_buildRoleAssignmentFromPS($psRoleAssignment, $role);
		}
		catch(BaseNotFoundException $e)
		{
			// Do nothing			
		}

        return $roleAssignment;
	}

	public function getRoleAssignments(BaseRole $role)
	{
        if ($this->permissionResolver->hasAccess('role', 'read') !== true) 
        {
            throw new UnauthorizedException('role', 'read');
        }

        $psRoleAssignments = $this->roleHandler->loadRoleAssignmentsByRoleId($role->getId());
        $roleAssignments = [];
        foreach($psRoleAssignments as $psRoleAssignment)
        {
			try 
			{
	        	if (null !== ($roleAssignment = $this->_buildRoleAssignmentFromPS($psRoleAssignment, $role)))
	        	{
					$roleAssignments[] = $roleAssignment;
				}
			}
			catch(BaseNotFoundException $e)
			{
				// Do nothing
			}			
		}

        return $roleAssignments;
	}

	private function _buildRoleAssignmentFromPS(PSRoleAssignment $psRoleAssignment, BaseRole $role)
	{
		$roleAssignment = null;
        if (property_exists($psRoleAssignment, 'userId'))
        {
			$user = $this->engine->userService()->loadUser($psRoleAssignment->userId);
            $roleAssignment = $this->domainMapper->buildDomainUserRoleAssignmentObject(
                $psRoleAssignment,
                $user,
                $role
            );
		}
        else if (property_exists($psRoleAssignment, 'groupId'))
        {
			$userGroup = $this->engine->userService()->loadGroup($psRoleAssignment->groupId);
            $roleAssignment = $this->domainMapper->buildDomainUserGroupRoleAssignmentObject(
                $psRoleAssignment,
                $userGroup,
                $role
            );
		}
		
		return $roleAssignment;
	}

	public function getRoleAssignmentsForUser(User $user, $inherited = false)
	{
        if ($this->permissionResolver->hasAccess('role', 'read') !== true) 
        {
            throw new UnauthorizedException('role', 'read');
        }

        $roleAssignments = $this->roleHandler->loadRoleAssignmentsByUserId($user->getId());

        return $roleAssignments;
	}

	public function getRoleAssignmentsForUserGroup(UserGroup $userGroup)
	{
        if ($this->permissionResolver->hasAccess('role', 'read') !== true) 
        {
            throw new UnauthorizedException('role', 'read');
        }

        $roleAssignments = $this->roleHandler->loadRoleAssignmentsByGroupId($userGroup->getId());

        return $roleAssignments;
	}
	
	public function removeRoleAssignment(RoleAssignment $roleAssignment)
	{
        if ($this->permissionResolver->canUser('role', 'assign', $roleAssignment) !== true) 
        {
            throw new UnauthorizedException('role', 'assign');
        }

        $this->beginTransaction();
        try {
            $this->roleHandler->removeRoleAssignment($roleAssignment->getId());
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getLimitationType($identifier)
    {
        return $this->limitationService->getLimitationType($identifier);
    }
	
	/**
	* @inheritdoc
	* 
	*/
    public function getLimitationTypesByServicePermission($service, $permission)
    {
        if (empty($this->settings['policyMap'][$service][$permission])) {
            return array();
        }

        $types = array();
        try {
            foreach (array_keys($this->settings['policyMap'][$service][$permission]) as $identifier) {
                $types[$identifier] = $this->limitationService->getLimitationType($identifier);
            }
        } catch (LimitationNotFoundException $e) {
            throw new BadStateException(
                "{$service}/{$function}",
                "policyMap configuration is referring to non existing identifier: {$identifier}",
                $e
            );
        }

        return $types;
    }

    /**
     * Validates Policy context: Limitations on a service and function.
     *
     * @throws \Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException If the same limitation is repeated or if
     *                                                                   limitation is not allowed on service/function
     *
     * @param string $service
     * @param string $function
     * @param \Eki\NRW\Component\Base\Permission\Role\Limitation[] $limitations
     *
     * @return \Eki\NRW\Component\Core\Values\ValidationError[][]
     */
    protected function validatePolicy($service, $permission, array $limitations)
    {
        if ($service !== '*' && $permission !== '*' && !empty($limitations)) {
            $limitationSet = array();
            foreach ($limitations as $limitation) {
                if (isset($limitationSet[$limitation->getIdentifier()])) {
                    throw new InvalidArgumentException(
                        'limitations',
                        "'{$limitation->getIdentifier()}' was found several times among the limitations"
                    );
                }

                if (!isset($this->settings['policyMap'][$service][$permission][$limitation->getIdentifier()])) {
                    throw new InvalidArgumentException(
                        'policy',
                        "The limitation '{$limitation->getIdentifier()}' is not applicable on '{$service}/{$permission}'"
                    );
                }

                $limitationSet[$limitation->getIdentifier()] = true;
            }
        }

        return $this->limitationService->validateLimitations($limitations);
    }
}
