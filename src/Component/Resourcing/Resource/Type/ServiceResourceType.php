<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource\Type;

use Eki\NRW\Mdl\REA\Methods;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ServiceResourceType extends WorkResourceType
{
	/**
	* @inheritdoc
	*/
	public function getResourceType()
	{
		return 'service';		
	}

	/**
	* @inheritdoc
	*/
	public function normalize()
	{
		parent::normalize();
		
		if ($this->hasElement('process'))
		{
			$element = $this->getElement('process');
			$element->setAttribute('available_output_types', array(
				Methods::OUTPUT_PRODUCE, 
			));
		}
	}
}
