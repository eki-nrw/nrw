<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Persistence\Doctrine;

use Eki\NRW\Component\SPBase\Persistence\TransactionHandler as TransactionHandlerInterface;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 * The Persistence Transaction handler for Storage Engine.
 *
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
class TransactionHandler implements TransactionHandlerInterface
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $connection;
	
	public function __construct(ObjectManager $objectManager)
	{
		$this->connectioon = $objectManager->getConnection();
	}
	
    /**
	* @inheritdoc
	* 
	*/
    public function beginTransaction()
    {
        try {
            $this->connection->beginTransaction();
        } catch (DBALException $e) {
            throw $e;
        }
	}

    /**
	* @inheritdoc
	* 
	*/
    public function commit()
    {
        try {
            $this->connection->commit();
        } catch (DBALException $e) {
            throw $e;
        }
    }

    /**
	* @inheritdoc
	* 
	*/
    public function rollback()
    {
        try {
            $this->connection->rollBack();
        } catch (DBALException $e) {
            throw $e;
        }
    }
}
