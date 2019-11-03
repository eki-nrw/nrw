<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\REA\Resource;

use Eki\NRW\Mdl\REA\Resource\AbstractResource as BaseAbstractResource;
use Eki\NRW\Common\Element\HasElementsTrait;
use Eki\NRW\Common\Extension\BaseBuildSubjectTrait;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class AbstractResource extends BaseAbstractResource implements ResourceInterface
{
	use
		HasElementsTrait,
		BaseBuildSubjectTrait
	;

	/**
	* Magic __call used by PropertyAccess component of Symfony
	* 
	* @param string $name
	* @param array $args
	* 
	* @return mixed
	*/
/*	
	public function __call($name, $args)
    {
        $property = lcfirst(substr($name, 3));
        if ('get' === substr($name, 0, 3)) 
        {
        	if (null !== ($attrVal = $this->getAttribute($property)))
        		return $attrVal;
        	else if (null !== ($optVal = $this->getOption($property)))
        		return $optVal;
        	else if (null !== ($propVal = $this->getProperty($property)))
        		return $propVal;
        } 
        elseif ('set' === substr($name, 0, 3)) 
        {
        	if (count($args) === 1)
        	{
				if ($this->hasAttribute($property))
					$this->setAttribute($property, $args);	
				else if ($this->hasOption($property))
					$this->setOption($property, $args);	
				else if ($this->hasProperty($property))
					$this->setProperty($property, $args);	
			}
        }
    }
*/
}
