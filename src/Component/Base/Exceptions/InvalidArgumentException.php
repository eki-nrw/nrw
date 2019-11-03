<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Exceptions;

/**
 * This exception is thrown if a service method is called with an illegal or non appropriate value.
 */
abstract class InvalidArgumentException extends ForbiddenException
{
}
