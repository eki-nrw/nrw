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
class InvalidArgumentValue extends InvalidArgumentException
{
    /**
     * Generates: "Argument '{$argumentName}' is invalid: '{$value}' is wrong value[ in class '{$className}']".
     *
     * @param string $argumentName
     * @param mixed $value
     * @param string|null $className Optionally to specify class in abstract/parent classes
     * @param \Exception|null $previous
     */
    public function __construct($argumentName, $value, $className = null, Exception $previous = null)
    {
        $valueStr = is_string($value) ? $value : var_export($value, true);
        $parameters = ['%actualValue%' => $valueStr];
        $this->setMessageTemplate("'%actualValue%' is wrong value");
        if ($className) {
            $this->setMessageTemplate("'%actualValue%' is wrong value in class '%className%'");
            $parameters['%className%'] = $className;
        }
        $whatIsWrong = $this->getMessageTemplate();

        parent::__construct($argumentName, $whatIsWrong, $previous);

        // Alter the message template & inject new parameters.
        /** @Ignore */
        $this->setMessageTemplate(str_replace('%whatIsWrong%', $whatIsWrong, $this->getMessageTemplate()));
        $this->addParameters($parameters);
        $this->message = $this->getBaseTranslation();
    }
}
