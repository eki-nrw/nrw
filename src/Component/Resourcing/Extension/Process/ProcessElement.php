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

use Eki\NRW\Mdl\REA\Methods;
use Eki\NRW\Component\REA\Resource\AbstractResourceTypeElement;

use Symfony\Component\OptionsResolver\OptionsResolver; 

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ProcessElement extends AbstractResourceTypeElement
{
	/**
	* @inheritdoc
	*/
	protected function configureElement(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			array(
				'available_input_types' => array(),
				'available_output_types' => array(),
			)
		);
		
		$resolver->setRequired(array('available_input_types', 'available_output_types'));
		
		$resolver->setAllowedTypes('available_input_types', 'array');
		$resolver->setAllowedTypes('available_output_types', 'array');
		
		$resolver->setAllowedValues('available_input_types', function($value) {
			if (!is_array($value))
				return false;
			if (empty($value))
				return true;
			
			foreach($value as $elem)
			{
				if (!in_array($elem, array(
					Methods::INPUT_USE,
					Methods::INPUT_CONSUME,
					Methods::INPUT_CITE,
					Methods::INPUT_WORK,
					Methods::INPUT_ACCEPT,
					Methods::INPUT_LOAD,
				)))
					return false;
			}
			
			return true;
		});

		$resolver->setAllowedValues('available_output_types', function($value) {
			if (!is_array($value))
				return false;
			if (empty($value))
				return true;
			
			foreach($value as $elem)
			{
				if (!in_array($elem, array(
					Methods::OUTPUT_PRODUCE,
					Methods::OUTPUT_IMPROVE,
					Methods::OUTPUT_UNLOAD
				)))
					return false;
			}
			
			return true;
		});
	}
}
