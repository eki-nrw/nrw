<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Permission\Role\Role;

use Eki\NRW\Component\SPBase\Persistence\BaseHandler;

use Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role as PSRole;

use Eki\NRW\Component\Core\Persistence\Permission\Role\Role;

use Eki\NRW\Component\Base\Core\Exceptions\NotFoundException;
use Eki\NRW\Component\Base\Core\Exceptions\InvalidArgumentException;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Handler extends BaseHandler
{
	/**
	* Create a new role entity in a storage engine
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role
	*/
	public function createRole($identifier)
	{
		if (null !== $this->loadRoleByIdentifier($identifier))
		{
			throw new InvalidArgumentException("identifier", "Already role with identifier $identifier.");
		}
		
		$role = new Role(array('identifier' => $identifier));
		
		try 
		{
			$this->updateRole($role);
		}
		catch(\Exception $e)
		{
			throw $e;
		}

		return $this->loadRole($role->getId());
	}
	
	/**
	* Load role object
	* 
	* @param int|string $id
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role
	*/
	public function loadRole($id)
	{
		if (null !== ($role = $this->getObjectFromCache($id)))
			return $role;

		$role = $this->findRes($id);		
		if (null === $role)
            throw new NotFoundException('Role', $id);
		
		$this->setObjectToCache($role);

		return $role;
	}

	/**
	* Load role by identifier
	* 
	* @param string $identifier
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role
	*/
	public function loadRoleByIdentifier($identifier)
	{
		if (null !== ($role = $this->getObjectFromCache($identifier, 'identifier')))
			return $role;
				
		$role = $this->findResOneBy(array('identifier' => $identifier));
		if ($role === null)
            throw new NotFoundException('Role', array('identifier'=>$identifier));

		$this->setObjectToCache($role, 'identifier');
			
		return $role;
	}
	
	/**
	* Load all roles
	* 
	* @return \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role[]
	*/
	public function loadRoles()
	{
		$arrayObject = $this->getObjectFromCache("allRoles", "arrayObject");
		if ($arrayObject !== null)
		{
			return $arrayObject->getArrayCopy();
		}

		$loadedRoles = [];
		foreach($this->findAll() as $role)
		{
			$loadedRoles[] = $this->loadRole($role->getId());
		}

		$this->setObjectToCache(new ArrayObject($loadedRoles), "allRoles".$roleId);
		
		return $loadedRoles;
	}

	/**
	* Delete given role
	* 
	* @param \Eki\NRW\Component\SPBase\Perssistence\Permission\Role\Role $role
	* 
	* @return void
	*/	
	public function deleteRole(PSRole $role)
	{
		$this->clearObjectFromCache($role, 'identifier');
		$this->clearObjectFromCache($role);
		$this->objectManager->remove($role);
	}
	
	/**
	* Update a role
	* 
	* @param \Eki\NRW\Component\SPBase\Persistence\Permission\Role\Role $role
	* 
	* @return void
	*/
	public function updateRole(PSRole $role)
	{
		$this->setObjectToCache($role, 'identifier');
		$this->setObjectToCache($role);
		$this->objectManager->persist($role);
	}
}
