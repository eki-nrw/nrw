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

use Eki\NRW\Component\Base\Engine\Translatable;

/**
 * Interface for validation errors.
 *
 * Enforces to return a translatable message, since it will be necessary to
 * present validation errors to the user. Thus we need plural form handling and
 * replacements of placeholders and so on.
 */
interface ValidationError extends Translatable
{
    /**
     * Sets the target element on which the error occurred.
     *
     * E.g. Property of a Field value which didn't validate against validation.
     * Can be a property path compatible with Symfony PropertyAccess component.
     *
     * Examples:
     * - "[StringLengthValidator][minStringLength]" => Target is "minStringLength" key under "StringLengthValidator" key (fieldtype validator configuration)
     * - "my_field_definition_identifier"
     *
     * @param string $target
     */
    public function setTarget($target);

    /**
     * Returns the target element on which the error occurred.
     *
     * @return string
     */
    public function getTarget();
}
