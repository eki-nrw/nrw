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
use Eki\NRW\Component\Base\Persistence\Working\Handler;
use Eki\NRW\Component\Base\Engine\PermissionResolver;
use Eki\NRW\Component\Notification\NotificatorInterface;

use Eki\NRW\Component\Base\Engine\WorkingService as WorkingServiceInterface;
use Eki\NRW\Component\Base\Engine\Working\SubjectService as BaseSubjectService;
use Eki\NRW\Component\Base\Engine\Working\WorkingSubjectService as BaseWorkingSubjectService;

use Eki\NRW\Component\Core\Engine\Working\SubjectService;
use Eki\NRW\Component\Core\Engine\Working\WorkingSubjectService;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;

/**
 * Working Service implementation
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class WorkingService extends BaseService implements WorkingServiceInterface
{
	/**
	* @var \Eki\NRW\Component\Base\Persistence\Working\Handler
	*/
	protected $workingHandler;
	
	/**
	* @var BaseWorkingSubjectService
	*/
	protected $workingSubjectService;

	/**
	* @var BaseSubjectService
	*/	
	protected $subjectService;
	
	public function __construct(
		Engine $engine,
		array $settings,
		PermissionResolver $permissionResolver,
		NotificatorInterface $notificator,
		Handler $handler
	)
	{
		$this->workingHandler = $handler;

		parent::__construct($engine, $settings, $persistenceHandler, $permissionResolver, $notificator);
	}

	/**
	* @inheritdoc
	*/
	public function workingSubjectService()
	{
		if ($this->workingSubjectService === null)
		{
			$this->workingSubjectService = new WorkingSubjectService(
				$this->engine,
				$this->getSettings('working_subject'),
				$this->permissionResolver,
				$this->notificator,
				$this->workingHandler->workingSubjectHandler(),
				$this->getActionHandlers($this->getSettings('working_subject'))
			);
		}
		
		return $this->workingSubjectService;		
	}

	protected function getSubjectDirector(array $settings)
	{
		$registries = [];
		foreach($settings['subject_types'] as $type => $subject)
		{
			$registry = $subject;
			$registry['type'] = $type;
			$registries[] = $registry;
		}
		
		$subjectBuilderClass = $settings['subjectBuilderClass'];
		$subjectDirectorClass = $settings['subjectDirectorClass'];
		
		$director = new $subjectDirectorClass($registries, $subjectBuilderClass);
		
		return $director;
	}
	
	protected function getActionHandlers(array $settings)
	{
		$subjectClasses = [];
		foreach($settings['subjects'] as $subjectType => $subjectClass)
		{
			$subjectClasses[] = $subjectClass;
		}
		
		$actionHandlerClass = $settings['actionHandlerClass'];
		$actionHandlers = [];
		foreach($settings['working_types'] as $workingType)
		{
			$handler = new $actionHandlerClass($subjectClasses);
			$handler->setWorkingType($workingType);
			
			$actionHandlers[$workingType] = $handler;
		}
		
		return $actionHandlers;
	}

	/**
	* @inheritdoc
	*/
	public function subjectService()
	{
		if ($this->subjectService === null)
		{
			$this->subjectService = new SubjectService(
				$this->engine,
				$this->getSettings('subject'),
				$this->permissionResolver,
				$this->notificator,
				$this->workingHandler->subjectHandler(),
				$this->getSubjectDirector($this->getSettings('subject'))
			);
		}

		return $this->subjectService;
	}
}
