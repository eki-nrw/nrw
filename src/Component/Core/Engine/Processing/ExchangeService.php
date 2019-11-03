<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Exchangeing;

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\PermissionResolver;
use Eki\NRW\Component\Base\Persistence\Processing\Exchange\Handler;
use Eki\NRW\Component\Notification\NotificatorInterface;

use Eki\NRW\Component\Base\Engine\Exchangeing\ExchangeService as ExchangeServiceInterface;
use Eki\NRW\Component\Processing\Exchange\ExchangeInterface;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\UnauthorizedException as BaseUnauthorizedException;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Exception;

/**
 * Exchange Service implementation
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class ExchangeService extends BaseService implements ExchangeServiceInterface
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
	public function createExchange($type)
	{
        if (!$this->permissionResolver->canUser('exchange', 'create', 
        	array(
        		'identifier' => $type
        	)
        )) 
        {
            throw new UnauthorizedException(
                'exchange',
                'create',
                array(
        			'identifier' => $type
                )
            );
        }
        
		$this->beginTransaction();
		try
		{ 
			$exchange = $this->handler->create($type);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $exchange;
	}

	/**
	* @inheritdoc
	*/	
	public function loadExchange($id)
	{
        if (!$this->permissionResolver->canUser('exchange', 'read', 
        	array(
        		'id' => $id
        	)
        )) 
        {
            throw new UnauthorizedException(
                'exchange',
                'read',
                array(
        			'id' => $id
                )
            );
        }

		try
		{
			$exchange = $this->andler->load($id);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Exchange',
                array(
                    'id' => $id
                ),
                $e
            );
		}
		
		return $exchange;
	}

	/**
	* @inheritdoc
	*/
	public function deleteExchange(ExchangeInterface $exchange)
	{
        if (!$this->permissionResolver->canUser('exchange', 'remove', $exchange)) 
        {
            throw new UnauthorizedException(
                'exchange',
                'remove',
                array(
                    'id' => $exchange->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->delete($exchange);

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
	public function updateExchange(ExchangeInterface $exchange)
	{
        if (!$this->permissionResolver->canUser('exchange', 'edit', $exchange)) 
        {
            throw new UnauthorizedException(
                'exchange',
                'edit',
                array(
                    'id' => $exchange->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->handler->update($exchange);

			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $exchange;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function isFrame(FrameInterface $frame)
	{
		return $frame instanceof ExchangeInterface;
	}
}
