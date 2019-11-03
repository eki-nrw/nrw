<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Permission\Role;

use Eki\NRW\Mdl\Permission\LimitationInterface;
use Eki\NRW\Component\Base\Engine\Values\ValueObject;

/**
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
abstract class Limitation extends ValueObject implements LimitationInterface
{
    /**
     * A read-only list of IDs or identifiers for which the limitation should be applied.
     *
     * The value of this property must conform to a hash, which means that it
     * may only consist of array and scalar values, but must not contain objects
     * or resources.
     *
     * @var mixed[]
     */
    public $limitationValues = array();
    
    /**
	* @inheritdoc
	* 
	*/
    public function getValues()
    {
		return $this->limitationValues;		
	}
}
