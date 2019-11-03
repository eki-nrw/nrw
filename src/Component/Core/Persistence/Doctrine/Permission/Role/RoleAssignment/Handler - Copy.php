<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Role\RoleAssignment;

use Eki\NRW\Component\SPBase\Persistence\BaseHandler;

use Eki\NRW\Component\SPBase\Permission\Role\RoleAssignment as PSRoleAssignment;

use Eki\NRW\Component\Core\Persistence\Permission\Role\RoleAssignment;

use Eki\NRW\Component\Base\Core\Exceptions\NotFoundException;
use Eki\NRW\Component\Base\Core\Exceptions\InvalidArgumentException;

use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Factory\FactoryInterface;
use Eki\NRW\Common\Res\Factory\Factory;

use Symfony\Component\Cache\Adapter\AdapterInteface as Cache;
use Doctrine\Common\Persistence\ObjectManager;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Handler extends BaseHandler
{
	/**
	* @var Factory
	*/
	private $factory;

	public function __construct(
		ObjectManager $objectManager,
		Cache $cache,
		MetadataInterface $metadata
	)
	{
		$factoryRegistries = [];
		foreach($metadata->getClasses() as $identifier => $class)
		{
			$factoryRegistries[$identifier] = $class; 	
		}
		$this->factory = new Factory($factoryRegistries);
		
		parent::_construct($objectManager, $cache, $metadata);
	}

	/**
	* Create an role assignment object
	* 
	* @param string $identifier Identifier represents assigned object class.
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment
	*/
	public function createRoleAssignment($identifier)
	{
		try 
		{
			$assignmen = $this->factory->createNew($identifier);
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Object identifier invalid.", $e);
		}
		
		$this->update($assignment);

		return $assignment;
	}
	
    /**
     * Loads role assignment for specified assignment ID.
     *
     * @param mixed $roleAssignmentId
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException If role assignment is not found
     *
     * @return \Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment
     */
    public function loadRoleAssignment($roleAssignmentId)
	{
		if (null !== ($assignment = $this->getObjectFromCache($roleAssignmentId)))
			return $assignment;

		$assignment = $this->findRes($roleAssignmentId);
		if (null === $assignment)
            throw new NotFoundException('RoleAssignment', $roleAssignmentId);
		
		$this->setObjectToCache($assignment);		

		return $assignment;
	}

	public function updateRoleAssignment(RoleAssignment $roleAssignment)
	{
		
	}
    
    /**
     * Loads roles assignments Role.
     *
     * Role Assignments with same roleId and limitationIdentifier will be merged together into one.
     *
     * @param mixed $roleId
     *
     * @return \Eki\NRW\Component\SPBase\Permission\Role\RoleAssignment[]
     */
    public function loadRoleAssignmentsByRoleId($roleId)
    {
    	if (null === ($role = $this->loadRole($roleId)))
			throw new InvalidArgumentException("roleId", "No role with id $roleId.");
    	
		$cacheItem = $this->getCacheItem(BaseRoleAssignment::class, $roleId);	
		if (!$cacheItem->isHit())
		{
			$roleAssignment = $this->objectManager
				->getRepository($this->resMetadatas['roleAssignment']['class'])
				->findOneBy(array('role' => $role))
			;
			
			if (null === $roleAssignment)
	            throw new NotFoundException('RoleAssignment', $roleAssignmentId);
			
			$this->setCacheItem($roleAssignment);
		}
		else
		{
			$roleAssignment = $cacheItem->get();
		}

		return $roleAssignment;
	}

    /**
     * Loads roles assignments to a user/group.
     *
     * Role Assignments with same roleId and limitationIdentifier will be merged together into one.
     *
     * @param mixed $groupId In legacy storage engine this is the content object id roles are assigned to in ezuser_role.
     *                      By the nature of legacy this can currently also be used to get by $userId.
     * @param bool $inherit If true also return inherited role assignments from user groups.
     *
     * @return \Eki\NRW\Component\SPBase\Permission\Role\RoleAssignment[]
     */
    public function loadRoleAssignmentsByGroupId($groupId, $inherit = false)
    {
		
	}

	public function assignRoleToUser($userId, $roleId, array $limitations = array())
	{
		$user = $this->userHandler->loadUser($userId);
		$userRoleAssignment = new UserRoleAssignment($uer, $role, $roleLimitation);
	}
	
	public function unAssignRoleFromUser($userId, $roleId)
	{
		
	}
	
	public function assignRoleToUserGroup($userGroupId, $roleId, array $limitations = array())
	{
		
	}
	
	public function unAssignRoleFromUserGroup($userGroupId, $roleId)
	{
		
	}
	
	public function removeRoleAssignment($roleAssignmentId)
	{
		
	}
}
