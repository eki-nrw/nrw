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

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\PermissionResolver;
use Eki\NRW\Component\Notification\NotificatorInterface;

use Eki\NRW\Component\Base\Engine\Working\SubjectService as SubjectServiceInterface;
use Eki\NRW\Component\Core\Engine\BaseService;
use Eki\NRW\Component\Base\Persistence\Working\Subject\Handler;

use Eki\NRW\Mdl\Working\Subject\DirectorInterface as SubjectDirector;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

/**
 * Working Service implementation.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 *
 */
class SubjectService extends BaseService implements SubjectServiceInterface
{
	/**
	* @var Handler
	*/
	protected $subjectHandler;
	
	/**
	* @var \Eki\NRW\Mdl\Working\Subject\DirectorInterface
	*/
	protected $subjectDirector;
	
	public function __construct(
		Engine $engine,
		array $settings,
		PermissionResolver $permissionResolver,
		NotificatorInterface $notificator,
		Handler $handler,
		SubjectDirector $subjectDirector
	)
	{
		$this->subjectHandler = $handler;
		$this->subjectDirector = $subjectDirector;
		
		parent::__construct($engine, $settings, $permissionResolver, $notificator);
	}
	
	/**
	* @inheritdoc
	*/
	public function createSubject($identifier)
	{
		if (!$this->permissionResolver->canUser(
			'subject', 'create', 
			array(
				'identifier' => $identifier,
			)
		))
		{
            throw new UnauthorizedException(
				'subject', 'create', 
            	array(
            		'identifier' => $identifier,
            	)
            );
        }

        $this->beginTransaction();
        try 
        {
			$subject = $this->subjectHandler->createSubject($identifier);
			$subject = $this->subjectDirector->getBuilder($identifier)->setObject($subject)->build();
			$this->updateSubject($subject);		
			
            $this->engine->commit();
        } 
        catch (Exception $e) 
        {
            $this->rollback();
            throw $e;
        }

		// TODO: $this->notificator->trigger();
        
        return $subject;
	}
	
	/**
	* Checks if the service can create the given identifier of subject
	* 
	* @param string $identifier
	* 
	* @return bool
	*/
	public function canCreate($identifier)
	{
		return $this->subjectDirector->support($identifier);
	}

	/**
	* @inheritdoc
	*/
	public function loadSubject($subjectId)
	{
        if (!$this->permissionResolver->canUser(
			'subject', 'read', 
        	array(
        		'id' => $subjectId,
        	)
        )) 
        {
            throw new UnauthorizedException(
				'subject', 'read', 
            	array(
            		'id' => $subjectId,
            	)
            );
        }

		try
		{
			$subject = $this->subjectHandler->loadSubject($subjectId);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'subject',
                array(
                    'id' => $subjectId,
                ),
                $e
            );
		}
		
		return $subject;
	}

	/**
	* @inheritdoc
	*/
	public function deleteSubject($subject)
	{
        if (!$this->permissionResolver->canUser(
			'subject', 'remove', 
        	array($subject)
        )) 
        {
            throw new UnauthorizedException(
				'subject', 'remove',
                array(
                    'id' => $subject->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->subjectHandler->deleteSubject($subject);
			
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
	*/
	public function updateSubject($subject)
	{
        if (!$this->permissionResolver->canUser(
			'subject', 'edit', 
        	array($subject)
        )) 
        {
            throw new UnauthorizedException(
                'subject', 'edit',
                array(
                    'id' => $subject->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->subjectHandler->updateSubject($subject);
			
			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $subject;
	}
}
