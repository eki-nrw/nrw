<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Working;

use Eki\NRW\Component\Base\Engine\Working\WorkingSubjectService as WorkingSubjectServiceInterface;
use Eki\NRW\Component\Base\Engine\RelatingService;
use Eki\NRW\Component\Core\Engine\BaseService;

use Eki\NRW\Component\Base\Persistence\Working\WorkingSubject\Handler;
use Eki\NRW\Mdl\Working\WorkingSubject\ActionHandlerInterface;

use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Eki\NRW\Component\Core\Engine\Working\TypeMeaningHelper;

/**
 * Working Subject Service implementation.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 *
 */
class WorkingSubjectService extends BaseService implements WorkingSubjectServiceInterface
{
	/**
	* @var Handler
	*/
	protected $handler;
	
	/**
	* @var RelatingService
	*/
	protected $relatingService;
	
	/**
	* @var \Eki\NRW\Mdl\Working\WorkingSubject\DirectorInterface
	*/
	protected $workingDirector;
	
	/**
	* @var \Eki\NRW\Mdl\Working\WorkingSubject\ActionHandlerInterface[]
	*/
	protected $actionHandlers;
	
	/**
	* @var TypeMeaningHelper
	*/
	private $typeMeaningHelper;
	
	public function __construct(
		Engine $engine,
		array $settings,
		PermissionResolver $permissionResolver,
		NotificatorInterface $notificator = null
		Handler $handler,
		array $actionHandlers /* ActionHandlerInterface[] $actionHandlers */
	)
	{
		$this->handler = $handler;
		$this->actionHandlers = $actionHandlers;
		$this->relatingService = $this->engine->getRelatingService();

		$this->typeMeaningHelper = new TypeMeaningHelper();
		
		parent::__construct($engine, $settings, $permissionResolver, $notificator);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function createWorkingSubject($workingType)
	{
		if (!$this->permissionResolver->canUser(
			'working_subject', 'create', 
			array(
				'identifier' => $workingType
			)
		))
		{
            throw new UnauthorizedException(
				'working_subject', 'create', 
            	array(
            		'identifier' => $workingType,
            	)
            );
        }
        
		$this->beginTransaction();
		try 
		{
			$ws = $this->handler->createWorkingSubject($workingType);
			
			$this->commit();
		}
		catch(Exception $e)
		{
			$this->rollBack();
			throw $e;
		}
		
        return $ws;
	}	

	/**
	* @inheritdoc
	* 
	*/
	public function loadWorkingSubject($id)
	{
        if (!$this->permissionResolver->canUser(
        	'working_subject', 'read', 
        	array(
        		'id' => $id
        	)
        )) 
        {
            throw new UnauthorizedException(
        		'working_subject', 'read', 
                array(
        			'id' => $id
                )
            );
        }

		try
		{
			$ws = $this->handler->load($id);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'WorkingSubject',
                array(
                    'id' => $id
                ),
                $e
            );
		}
		
		return $ws;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function updateWorkingSubject(WorkingSubjectInterface $ws)
	{
        if (!$this->permissionResolver->canUser('working_subject', 'edit', $ws)) 
        {
            throw new UnauthorizedException(
                'working_subject',
                'edit',
                array(
                    'id' => $ws->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->update($ws);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $agent;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function deleteWorkingSubject(WorkingSubjectInterface $ws)
	{
        if (!$this->permissionResolver->canUser('working_subject', 'remove', $ws)) 
        {
            throw new UnauthorizedException(
                'working_subject', 'remove',
                array(
                    'id' => $ws->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->delete($ws);

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
	public function findWorkingSubject($workingType, $subject)
	{
        if (!is_object($subject) or empty($subject))
        {
			throw new InvalidArgumentException("subject", "Subject must be object");
		}

		return $this->handler->findWorkingSubject($workingType, $subject);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function getContinuedSubjectType($subjectType, $workingType, $continuation, array $contexts = [])
	{
		if (isset($this->settings['working_subject'][$workingType]['continuation'][$continuation][$subjectType]))
			return $this->settings['working_subject'][$workingType]['continuation'][$continuation][$subjectType];
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getContinuedSubject($subject, $workingType, $continuation, $back = false)
	{
		if (!$back)
		{
			$relationship = $this->relatingService->getRelation(
				$subject, 
				"relationship", 
				$this->typeMeaningHelper->setWorkingType($workingType)->setContinuation($continuation)->getType()
			);	
			if ($relationship !== null)
			{
				return $relationship->getOtherObject();
			}
		}
		else
		{
			$relationship = $this->relatingService->getRelationOf(
				$subject, 
				"relationship", 
				$this->typeMeaningHelper->setWorkingType($workingType)->setContinuation($continuation)->getType()
			);	
			if ($relationship !== null)
			{
				return $relationship->getObject();
			}
		}		
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function continuingSubjects($subject, $continuedSubject, $workingType, $continuation)
	{
		$relationship = $this->relatingService->createRelation(
			"relationship", 
			$this->typeMeaningHelper->setWorkingType($workingType)->setContinuation($continuation)->getType()
		);
		$relationship = $this->relatingService->linkRelationship($relationship, $subject, $continuedSubject);
		
		return $relationship;
	}
}
