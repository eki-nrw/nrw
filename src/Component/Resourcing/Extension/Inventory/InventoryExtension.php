<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Extension\Inventory;

use Eki\NRW\Component\REA\Resource\AbstractResourceTypeExtension;
use Eki\NRW\Mdl\REA\ResourceNRW\ComponentResourceTypeInterface;
use Eki\NRW\Common\Extension\ExtensionPositions;
use Eki\NRW\Common\Extension\BuilderInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class InventoryExtension extends AbstractResourceTypeExtension
{
	/**
	* @inheritdoc
	*/
	public function getExtensionName()
	{
		return 'inventory';		
	}
	
	/**
	* @inheritdoc
	*/
	public function support($subject, $position = null)
	{
		if (true !== parent::support($subject, $position))
			return false;	

		if ($position === ExtensionPositions::POS_BUILD)
			return true;

		if ($position === ExtensionPositions::POS_OPERATION)
			return true;
	}
	
	public function apply($subject, $position, array $contexts = [])
	{
		if ($position === ExtensionPositions::POS_OPERATION)
		{
			$this->operate($subject, $contexts['command'], $contexts);
		}
	}
	
	protected function operate(ResourceTypeInterface $resourceType, array $cmd, array $contexts)
	{
	}
	
	/**
	* @inheritdoc
	*/
	public function build(BuilderInterface $builder, array $contexts = [])
	{
	}
}
