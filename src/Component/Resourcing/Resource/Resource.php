<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Resourcing\Resource;

use Symfony\Component\OptionsResolver\OptionsResolver; 

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Resource extends AbstractResource
{
	/**
	* @inheritdoc
	*/
    public function configureProperties(OptionsResolver $resolver)
    {
		$this->getResourceType()->configureProperties($resolver);
	}

	/**
	* @inheritdoc
	*/
    public function configureOptions(OptionsResolver $resolver)
    {
		$this->getResourceType()->configureOptions($resolver);
	}

	/**
	* @inheritdoc
	*/
    public function configureAttributes(OptionsResolver $resolver)
    {
		$this->getResourceType()->configureAttributes($resolver);
	}
}
