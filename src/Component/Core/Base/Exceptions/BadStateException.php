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

use Eki\NRW\Component\Base\Engine\Exceptions\BadStateException as APIBadStateException;
use Exception;
use Eki\NRW\Component\Core\Base\Translatable;
use Eki\NRW\Component\Core\Base\TranslatableBase;

/**
 * BadState Exception implementation.
 *
 * Usage: throw new BadState( 'nodes', 'array' );
 */
class BadStateException extends APIBadStateException implements Translatable
{
    use TranslatableBase;

    /**
     * Generates: "Argument '{$argumentName}' has a bad state: {$whatIsWrong}".
     *
     * @param string $argumentName
     * @param string $whatIsWrong
     * @param \Exception|null $previous
     */
    public function __construct($argumentName, $whatIsWrong, Exception $previous = null)
    {
        $this->setMessageTemplate("Argument '%argumentName%' has a bad state: %whatIsWrong%");
        $this->setParameters(['%argumentName%' => $argumentName, '%whatIsWrong%' => $whatIsWrong]);
        parent::__construct($this->getBaseTranslation(), 0, $previous);
    }
}
