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
use Eki\NRW\Component\SPBase\Permission\Role\Role as PSRole;

use Eki\NRW\Component\Core\Persistence\Permission\Role\RoleAssignment;
use Eki\NRW\Component\Core\Persistence\Permission\Role\Role;

use Eki\NRW\Component\Base\Core\Exceptions\NotFoundException;
use Eki\NRW\Component\Base\Core\Exceptions\InvalidArgumentException;

use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Factory\FactoryInterface;
use Eki\NRW\Common\Res\Factory\Factory;

use Symfony\Component\Cache\Adapter\AdapterInteface as Cache;
use Doctrine\Common\Persistence\ObjectManager;

use ArrayObject;

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
	* @param \Eki\NRW\Component\SPBase\Permission\Role\Role $role
	* @param object $assignedObj
	* 
	* @return \Eki\NRW\Component\Base\Engine\Permission\Role\RoleAssignment
	*/
	public function create($identifier, PSRole $role, $assignedObj)
	{
		if (null === $role)
			throw new InvalidArgumentException("role", "role cannot null");
		if (!is_object($assignedObj))
			throw new InvalidArgumentException("assignedObj", "assignedObj must be object.");
			
		try 
		{
			$roleAssignmen = $this->factory->createNew($identifier, array($assignedObj, $role));
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Object identifier invalid.", $e);
		}
		
		$this->update($roleAssignment);

		return $roleAssignment;
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
    public function load($roleAssignmentId)
	{
		if (null !== ($roleAssignment = $this->getObjectFromCache($roleAssignmentId)))
			return $roleAssignment;

		$roleAssignment = $this->findRes($roleAssignmentId);
		if (null === $roleAssignment)
            throw new NotFoundException('RoleAssignment', $roleAssignmentId);
		
		$this->setObjectToCache($roleAssignment);		

		return $roleAssignment;
	}
	
	public function update(PSRoleAssignment $roleAssignment)
	{
		$arrayObject = $this->getObjectFromCache("byRoleId-".$roleAssignment->roleId, "arrayObject");
		if ($arrayObject !== null)
		{
			$arrayObject->append($roleAssignment);
			$this->setObjectToCache($arrayObject, "byRoleId-".$roleAssignment->roleId);
		}
		
		$this->setObjectToCache($roleAssignment);
		$this->objectManager->persist($roleAssignment);
	}

	public function delete(PSRoleAssignment $roleAssignment)
	{
		$this->clearObjectFromCache($roleAssignment);
		$this->objectManager->remove($roleAssignment);
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
    public function loadAllByRoleId($roleId)
    {
    	if (null === ($role = $this->loadRole($roleId)))
			throw new InvalidArgumentException("roleId", "No role with id $roleId.");

		$arrayObject = $this->getObjectFromCache("byRoleId-".$roleId, "arrayObject");
		if ($arrayObject !== null)
		{
			return $arrayObject->getArrayCopy();
		}

		$assignments =  $this->findResBy(array('roleId' => $roleId));
		if (empty($assignments))
			return array();

		$this->setObjectToCache(new ArrayObject($assignments), "byRoleId-".$roleId);
		
		return $assignment;
	}
}
