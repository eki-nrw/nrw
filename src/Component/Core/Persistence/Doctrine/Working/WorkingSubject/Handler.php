<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Working\WorkingSubject;

use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\SPBase\Persistence\Working\WorkingSubject\Handler as HandlerInterface;
use Eki\NRW\Component\Working\WorkingSubject\WorkingSubjectInterface;

use Eki\NRW\Common\Res\Factory\FactoryInterface;
use Eki\NRW\Common\Res\Factory\Factory;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class Handler extends BaseHandler implements HandlerInterface
{
	/**
	* @var Factory
	*/
	protected $factory;

	/**
	* Constructor
	* 
	* @param ObjectManager $objectManager
	* @param Cache $cache
	* @param string $defaultCachePrefix
	* 
	*/
	public function __construct(
		ObjectManager $objectManager,
		Cache $cache,
		MetadataInterface $metadata
	)
	{
		parent::__construct($objectManager, $cache, $metadata);

		$factoryRegistries = [];
		foreach($metadata->getClasses() as $identifier => $class)
		{
			$factoryRegistries[$identifier] = $class; 	
		}
		
		$this->factory = new Factory($factoryRegistries);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function createWorkingSubject($workingType)
	{
		try 
		{
			$ws = $this->factory->createNew($workingType, array($workingType));
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Working type invalid.", $e);
		}
		
		$this->updateWorkingSubject($ws);

		return $ws;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function loadWorkingSubject($id)
	{
		if (null !== ($ws = $this->getObjectFromCache($id)))
			return $ws;

		$ws = $this->findRes($id);		
		if (null === $ws)
            throw new NotFoundException('Working Subject', $id);
		
		$this->setObjectToCache($ws);
		$this->setObjectToCache($ws, 'workingType+subjectId');

		return $ws;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function findWorkingSubject($workingType, $subject)
	{
		if (null !== ($ws = $this->getObjectFromCache($workingType."+".$subject->getId(), "workingType+subjectId")))
			return $ws;
		
		$ws = $this->findResOneBy(
			array(
				'workingType' => $workingType,
				'subjectId' => $subject->getId()
			), 
			$workingType
		);

		$this->setObjectToCache($ws);
		$this->setObjectToCache($ws, 'workingType+subjectId');
		
		return $ws;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function updateWorkingSubject(WorkingSubjectInterface $workingSubject)
	{
		if ($workingSubject->getSubjectId() !== null)
		{
			$this->setObjectToCache($workingSubject, 'workingType+subjectId');
		}
		
		$this->setObjectToCache($workingSubject);
		$this->objectManager->persist($workingSubject);
	}
	
	private function cacheKeyComplex(WorkingSubjectInterface $workingSubject, $forTag = false)
	{
		return $this->cacheKeyComplexByIdentifier(
			$workingSubject->getWorkingType(), 
			$workingSubject->getSubject()->getId(), 
			$forTag
		);
	}

	private function cacheKeyComplexByIdentifier($workingType, $subjectId, $forTag = false)
	{
		return 
			"working-" .
			$workingType . 
			$forTag ? "-tag-" : "-cache-" . 
			$subjectId
		;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function deleteWorkingSubject(WorkingSubjectInterface $workingSubject)
	{
		if (null !== ($subject = $workingSubject->getSubject()))
		{
			$this->clearObjectFromCache($workingSubject, 'workingType+subjectId');
		}
		
		$this->clearObjectFromCache($workingSubject);
		$this->objectManager->remove($workingSubject);
	}
}
