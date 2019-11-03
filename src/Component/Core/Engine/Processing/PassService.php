<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Passing;

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\PermissionResolver;
use Eki\NRW\Component\Base\Persistence\Passing\Pass\Handler;
use Eki\NRW\Component\Notification\NotificatorInterface;

use Eki\NRW\Component\Base\Engine\Passing\PassService as PassServiceInterface;
use Eki\NRW\Component\Passing\Pass\PassInterface;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\UnauthorizedException as BaseUnauthorizedException;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Exception;

/**
 * Pass Service implementation
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class PassService extends BaseService implements PassServiceInterface
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
	public function createPass($type)
	{
        if (!$this->permissionResolver->canUser('pass', 'create', 
        	array(
        		'identifier' => $type
        	)
        )) 
        {
            throw new UnauthorizedException(
                'pass',
                'create',
                array(
        			'identifier' => $type
                )
            );
        }
        
		$this->beginTransaction();
		try
		{ 
			$pass = $this->handler->create($type);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $pass;
	}

	/**
	* @inheritdoc
	*/	
	public function loadPass($id)
	{
        if (!$this->permissionResolver->canUser('pass', 'read', 
        	array(
        		'id' => $id
        	)
        )) 
        {
            throw new UnauthorizedException(
                'pass',
                'read',
                array(
        			'id' => $id
                )
            );
        }

		try
		{
			$pass = $this->andler->load($id);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Pass',
                array(
                    'id' => $id
                ),
                $e
            );
		}
		
		return $pass;
	}

	/**
	* @inheritdoc
	*/
	public function deletePass(PassInterface $pass)
	{
        if (!$this->permissionResolver->canUser('pass', 'remove', $pass)) 
        {
            throw new UnauthorizedException(
                'pass',
                'remove',
                array(
                    'id' => $pass->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->delete($pass);

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
	public function updatePass(PassInterface $pass)
	{
        if (!$this->permissionResolver->canUser('pass', 'edit', $pass)) 
        {
            throw new UnauthorizedException(
                'pass',
                'edit',
                array(
                    'id' => $pass->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->update($pass);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $pass;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function isFrame(FrameInterface $frame)
	{
		return $frame instanceof PassInterface;
	}
}
