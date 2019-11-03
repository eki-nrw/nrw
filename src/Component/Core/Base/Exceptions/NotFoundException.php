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

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Core\Base\Translatable;
use Eki\NRW\Component\Core\Base\TranslatableBase;

use Exception;

/**
 */
class NotFoundException extends BaseNotFoundException implements Httpable, Translatable
{
    use TranslatableBase;

    /**
     * Generates: Could not find '{$what}' with identifier '{$identifier}'.
     *
     * @param string $what
     * @param mixed $identifier
     * @param \Exception|null $previous
     */
    public function __construct($what, $identifier, Exception $previous = null)
    {
        $identifierStr = is_string($identifier) ? $identifier : var_export($identifier, true);
        $this->setMessageTemplate("Could not find '%what%' with identifier '%identifier%'");
        $this->setParameters(['%what%' => $what, '%identifier%' => $identifierStr]);
        parent::__construct($this->getBaseTranslation(), self::NOT_FOUND, $previous);
    }
}
