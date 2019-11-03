<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Base\Exceptions;

use Exception;

/**
 * Invalid Argument Type Exception implementation.
 *
 * Usage: throw new InvalidArgument( 'nodes', 'array' );
 */
class InvalidArgumentType extends InvalidArgumentException
{
    /**
     * Generates: "Argument '{$argumentName}' is invalid: expected value to be of type '{$expectedType}'[, got '{$value}']".
     *
     * @param string $argumentName
     * @param string $expectedType
     * @param mixed|null $value Optionally to output the type that was received
     * @param \Exception|null $previous
     */
    public function __construct($argumentName, $expectedType, $value = null, Exception $previous = null)
    {
        $parameters = ['%expectedType%' => $expectedType];
        $this->setMessageTemplate("Argument '%argumentName%' is invalid: expected value to be of type '%expectedType%'");
        if ($value) {
            $this->setMessageTemplate("Argument '%argumentName%' is invalid: expected value to be of type '%expectedType%', got '%actualType%'");
            $actualType = is_object($value) ? get_class($value) : gettype($value);
            $parameters['%actualType%'] = $actualType;
        }

        /** @Ignore */
        $this->addParameters($parameters);
        $this->message = $this->getBaseTranslation();
    }
}
