<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Processing;

use Eki\NRW\Component\Base\Engine\Processing\FramingService as FramingServiceInterface;
use Eki\NRW\Component\Base\Persistence\Processing\Frame\Handler;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\UnauthorizedException as BaseUnauthorizedException;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Eki\NRW\Component\Processing\Frame\FrameInterface;
use Eki\NRW\Component\Processing\FrameInterface;
//use Eki\NRW\Component\Processing\Pass\Pass;
//use Eki\NRW\Component\Processing\Process\Process;
//use Eki\NRW\Component\Processing\Exchange\Exchange;

use Eki\NRW\Component\Processing\Actuate\Actuator\Registry as ActuatorRegistry;
use Eki\NRW\Component\Core\Engine\Processing\TypeMeaningHelper;

/**
 * Frame Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class FramingService extends BaseService implements FramingServiceInterface
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
	* @var ActuatorRegistry
	*/
	protected $actuatorRegistry;

	public function __construct(
		Engine $engine,
		array $settings,
		PermissionResolver $permissionResolver,
		NotificatorInterface $notificator,
		Handler $handler,
		ActuatorRegistry $actuatorRegistry
	)
	{
		$this->handler = $handler;
		$this->relatingService = $this->engine->getRelatingService();
		$this->actuatorRegistry = $actuatorRegistry;
		
		$this->typeMeaningHelper = new TypeMeaningHelper();
		
		parent::__construct($engine, $settings, $permissionResolver, $notificator);
	}

	/**
	* @inheritdoc
	* 
	*/
	public function createFrame($type)
	{
		if (!$this->permissionResolver->canUser(
			'processing_frame', 'create', 
			array(
				'identifier' => $type
			)
		))
		{
            throw new UnauthorizedException(
				'processing_frame', 'create', 
            	array(
            		'identifier' => $type,
            	)
            );
        }

		if (null === ($actuator = $this->actuatorRegistry->create($type)))
		{
			throw new InvalidArgumentException("type", "Cannot find actuator of type $type.");	
		}
        
		$this->beginTransaction();
		try 
		{
			$frame = $this->handler->createFrame($type);
			$frame->setActuator($actuator);
			$frame->updateFrame($frame);
			
			$this->commit();
		}
		catch(Exception $e)
		{
			$this->rollBack();
			throw $e;
		}
		
        return $frame;
	}	

	/**
	* @inheritdoc
	* 
	*/
	public function loadFrame($id)
	{
        if (!$this->permissionResolver->canUser(
        	'processing_frame', 'read', 
        	array(
        		'id' => $id
        	)
        )) 
        {
            throw new UnauthorizedException(
        		'processing_frame', 'read', 
                array(
        			'id' => $id
                )
            );
        }

		try
		{
			$frame = $this->handler->load($id);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Frame',
                array(
                    'id' => $id
                ),
                $e
            );
		}
		
		return $frame;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function updateFrame(FrameInterface $frame)
	{
        if (!$this->permissionResolver->canUser('processing_frame', 'edit', $frame)) 
        {
            throw new UnauthorizedException(
                'processing_frame',
                'edit',
                array(
                    'id' => $frame->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->update($frame);

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
	public function deleteFrame(FrameInterface $frame)
	{
        if (!$this->permissionResolver->canUser('processing_frame', 'remove', $frame)) 
        {
            throw new UnauthorizedException(
                'processing_frame', 'remove',
                array(
                    'id' => $frame->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->delete($frame);

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
	public function loadOrCreateFrame($type, FrameInterface $frame)
	{
		if (null !== ($frame = $this->handler->findFrame($type, $frame)))
			return $frame;
			
		$frame = $this->createFrame($type)
			->setFrame($frame)
		;
		$this->updateFrame($frame);
		
        return $frame;
	}

	/**
	* @inheritdoc
	* 
	*/
/*	
	public function getFrameCategory(FrameInterface $frame)
	{
		$processingService = $this->engine->processingService();
		if ($processingService->passService()->isFrame($frame))
			return "pass";
		if ($processingService->processService()->isFrame($frame))
			return "process";
		if ($processingService->exchangeService()->isFrame($frame))
			return "exchange";
			
		throw new InvalidArgumentException(sprintf(
			"frame", "'frame' parameter muse be one of instance of ['%s', '%s', '%s'].",
			Pass::class, Process::class, Exchange:class
		));
	}
*/
	
	/**
	* @inheritdoc
	* 
	*/
	public function getFrame($type, $frame)
	{
				
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getContinueFrameType($frameType, $type, $continuation, array $contexts = [])
	{
		if (isset($this->settings['processing_frame'][$type]['continuation'][$continuation][$frameType]))
			return $this->settings['processing_frame'][$type]['continuation'][$continuation][$frameType];
	}

	/**
	* @inheritdoc
	* 
	*/
	public function getContinuedFrame(FrameInterface $frame, $type, $continuation, $back = false)
	{
		if (!$back)
		{
			$relationship = $this->relatingService->getRelation(
				$frame, 
				"relationship", 
				$this->typeMeaningHelper->setProcessingType($type)->setContinuation($continuation)->getType()
			);	
			if ($relationship !== null)
			{
				return $relationship->getOtherObject();
			}
		}
		else
		{
			$relationship = $this->relatingService->getRelationOf(
				$frame, 
				"relationship", 
				$this->typeMeaningHelper->setProcessingType($type)->setContinuation($continuation)->getType()
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
	public function continuingFrames(FrameInterface $frame, FrameInterface $otherFrame, $type, $continuation)
	{
		$relationship = $this->relatingService->createRelation(
			"relationship", 
			$this->typeMeaningHelper->setProcessingType($type)->setContinuation($continuation)->getType()
		);
		$relationship = $this->relatingService->linkRelationship($relationship, $subject, $continuedSubject);
		
		return $relationship;
	}
}
