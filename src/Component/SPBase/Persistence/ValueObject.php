<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Persistence;

use Eki\NRW\Common\Common\ValueObject as BaseValueObject;

/**
 * Base Storage Persistence Value object.
 *
 * All properties of ValueObject *must* be serializable for cache & NoSQL use.
 */
abstract class ValueObject extends BaseValueObject
{
}
