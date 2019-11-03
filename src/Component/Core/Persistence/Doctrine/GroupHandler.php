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

use Eki\NRW\Common\Res\Metadata\MetadataInterface;
use Eki\NRW\Common\Res\Metadata\Registry;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Cache\Adapter\AdapterInterface as Cache;

/**
 * Abstract Persistence Group Handler implementation
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class GroupHandler extends AbstractHandler
{
	/**
	* @var Registry
	*/
	protected $registry;

	/**
	* Constructor
	* 
	* @param ObjectManager $objectManager
	* @param Cache $cache
	* @param array|MetadataInterface[] $metadatas
	* 
	*/
	public function __construct(
		ObjectManager $objectManager,
		Cache $cache,
		$metadatas
	)
	{
		parent::__construct($objectManager, $cache);
		
		if ($metadatas instanceof Registry)
		{
			$this->registry = $metadatas;
		}
		else if (is_array($metadatas))
		{
			$registry = new Registry();
			foreach($metadatas as $alias => $metadata)
			{
				if (!is_array($metadata) and !$metadata instanceof MetadataInterface)
				{
					throw new InvalidArgumentException(
						"metadatas", 
						sprintf(
							"One of metadatas that has key $alias is invalid. It must be array of instance of %s. Given %s.",
							MetadataInterface::class,
							gettype($metadata)
						)
					);
				}

				try 
				{
					if (is_array($metadata))
						$registry->addFrom($alias, $metadata);				
					else if ($metadata instanceof MetadataInterface)
						$registry->add($metadata);
				}
				catch (\Exception $e)
				{
					throw new InvalidArgumentException("metadatas", $e->getMessage());
				}
			}
			
			$this->registry = $registry;
		}
		else
			throw new InvalidArgumentException(sprintf("'metadatas' must be array of object of %s.", Registry::class));
	}
}
