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
class BaseResourceType extends ResourceType
{
	/**
	* @inheritdoc
	*/
	public function getResourceType()
	{
		return 'base';		
	}

	/**
	* @inheritdoc
	*/
	public function normalize()
	{
		if ($this->hasElement('process'))
		{
			$element = $this->getElement('process');
			$element->setAttribute('available_input_types', array(
				Methods::INPUT_USE, 
				Methods::INPUT_CONSUME, 
				Methods::INPUT_CITE, 
				Methods::INPUT_ACCEPT, 
				Methods::INPUT_LOAD, 
			));
			$element->setAttribute('available_output_types', array(
				Methods::OUTPUT_PRODUCE, 
				Methods::OUTPUT_IMPROVE, 
				Methods::OUTPUT_UNLOAD, 
			));
		}
	}
}
