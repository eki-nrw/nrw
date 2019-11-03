<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Base\Exceptions\NotFound;

use Eki\NRW\Component\Core\Base\Exceptions\Httpable;
use Exception;
use Eki\NRW\Component\Core\Base\TranslatableBase;
use Eki\NRW\Component\Core\Base\Translatable;
use RuntimeException;

/**
 * Limitation Not Found Exception implementation.
 */
class LimitationNotFoundException extends RuntimeException implements Httpable, Translatable
{
    use TranslatableBase;

    /**
     * Creates a Limitation Not Found exception with info on how to fix.
     *
     * @param string $limitation
     * @param \Exception|null $previous
     */
    public function __construct($limitation, Exception $previous = null)
    {
        $this->setMessageTemplate(
            "Limitation '%limitation%' not found, needs to be implemented or configured to use Limitation\\BlockingLimitationType (%ezpublish.api.role.limitation_type.blocking.class%"
        );
        $this->setParameters(['%limitation%' => $limitation]);

        parent::__construct($this->getBaseTranslation(), self::INTERNAL_ERROR, $previous);
    }
}
