<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Extension\Process;

use Eki\NRW\Component\REA\Resource\AbstractResourceTypeExtension;
use Eki\NRW\Common\Extension\ExtensionPositions;
use Eki\NRW\Common\Extension\BuilderInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessExtension extends AbstractResourceTypeExtension
{
	/**
	* @inheritdoc
	*/
	public function getExtensionName()
	{
		return 'process';		
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
	}
	
	public function apply($subject, $position, array $contexts = [])
	{
		
	}
	
	/**
	* @inheritdoc
	*/
	public function build(BuilderInterface $builder, array $contexts = [])
	{
		$builder
			->addElement(new ProcessElement($this->getExtensionName(), 'Available input/output type of process.'))
		;
	}
}
