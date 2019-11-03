<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Working\Subject;

use Eki\NRW\Component\Core\Persistence\Doctrine\BaseHandler;
use Eki\NRW\Component\SPBase\Persistence\Working\Subject\Handler as HandlerInterface;

use Eki\NRW\Common\Res\Metadata\Metadata;
use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Factory\Factory;

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
	*/
	public function createSubject($identifier)
	{
		try 
		{
			$subject = $this->factory->createNew($identifier);
		}
		catch(\InvalidArgumentException $e)
		{
			throw new InvalidArgumentException("identifier", "Subject identifier invalid.", $e);
		}
		
		$this->updateSubject($subject);

		return $subject;
	}

	/**
	* @inheritdoc
	*/
	public function loadSubject($subjectId)
	{
		if (null !== ($subject = $this->getObjectFromCache($subjectId)))
			return $subject;

		$subject = $this->findRes($subjectId);		
		if (null === $subject)
            throw new NotFoundException('Working', $subjectId);
		
		$this->setObjectToCache($subject);	

		return $subject;
	}
	
	public function deleteSubject($subject)
	{
		$this->clearObjectFromCache($subject);
		$this->objectManager->remove($subject);
	}
	
	public function updateSubject($subject)
	{
		$this->setObjectToCache($subject);
		$this->objectManager->persist($subject);
	}
}
