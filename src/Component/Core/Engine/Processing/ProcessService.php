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

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\PermissionResolver;
use Eki\NRW\Component\Base\Persistence\Processing\Process\Handler;
use Eki\NRW\Component\Notification\NotificatorInterface;

use Eki\NRW\Component\Base\Engine\Processing\ProcessService as ProcessServiceInterface;
use Eki\NRW\Component\Processing\Process\ProcessInterface;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\UnauthorizedException as BaseUnauthorizedException;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Exception;

/**
 * Process Service implementation
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class ProcessService extends BaseService implements ProcessServiceInterface
{
	/**
	* @var Handler
	*/
	protected $handler;
	
	public function __construct(
		Engine $engine,
		array $settings,
		PermissionResolver $permissionResolver,
		NotificatorInterface $notificator,
		Handler $handler
	)
	{
		$this->handler = $handler;		
		
		parent::__construct($engine, $settings, $permissionResolver, $notificator);
	}

	/**
	* @inheritdoc
	*/
	public function createProcess($type)
	{
        if (!$this->permissionResolver->canUser('process', 'create', 
        	array(
        		'identifier' => $type
        	)
        )) 
        {
            throw new UnauthorizedException(
                'process',
                'create',
                array(
        			'identifier' => $type
                )
            );
        }
        
		$this->beginTransaction();
		try
		{ 
			$process = $this->handler->create($type);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $process;
	}

	/**
	* @inheritdoc
	*/	
	public function loadProcess($id)
	{
        if (!$this->permissionResolver->canUser('process', 'read', 
        	array(
        		'id' => $id
        	)
        )) 
        {
            throw new UnauthorizedException(
                'process',
                'read',
                array(
        			'id' => $id
                )
            );
        }

		try
		{
			$process = $this->andler->load($id);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Process',
                array(
                    'id' => $id
                ),
                $e
            );
		}
		
		return $process;
	}

	/**
	* @inheritdoc
	*/
	public function deleteProcess(ProcessInterface $process)
	{
        if (!$this->permissionResolver->canUser('process', 'remove', $process)) 
        {
            throw new UnauthorizedException(
                'process',
                'remove',
                array(
                    'id' => $process->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->delete($process);

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
	public function updateProcess(ProcessInterface $process)
	{
        if (!$this->permissionResolver->canUser('process', 'edit', $process)) 
        {
            throw new UnauthorizedException(
                'process',
                'edit',
                array(
                    'id' => $process->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->update($process);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $process;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function isFrame(FrameInterface $frame)
	{
		return $frame instanceof ProcessInterface;
	}
}
