<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine\Working;

use Eki\NRW\Component\Core\Persistence\Doctrine\GroupHandler;
use Eki\NRW\Component\SPBase\Persistence\Working\Handler as HandlerInterface;
use Eki\NRW\Component\Core\Persistence\Doctrine\Working\WorkingSubject\Handler as WorkingSubjectHandler;
use Eki\NRW\Component\Core\Persistence\Doctrine\Working\Subject\Handler as SubjectHandler;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class Handler extends GroupHandler implements HandlerInterface
{
	/**
	* @var WorkingSubjectHandler
	*/
	protected $workingSubjectHandler;

	/**
	* @var SubjectHandler
	*/	
	protected $subjectHandler;
	
	/**
	* @inheritdoc
	* 
	*/
	public function workingSubjectHandler()
	{
		if ($this->workingSubjectHandler === null)
		{
			$this->workingSubjectHandler = new WorkingSubjectHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get('working_subject')
			);
		}
		
		return $this->workingSubjectHandler;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function subjectHandler()
	{
		if ($this->subjectHandler === null)
		{
			$this->subjectHandler = new SubjectHandler(
				$this->objectManager,
				$this->cache,
				$this->registry->get("subject")
			);
		}
		
		return $this->subjectHandler;
	}
}
