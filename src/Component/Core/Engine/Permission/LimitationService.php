<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine\Permission;

use Eki\NRW\Component\Base\Engine\Persistence\Permission\Role\Limitation;

use Eki\NRW\Component\Core\Base\Exceptions\NotFound\LimitationNotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\BadStateException;

/**
 * Internal service to deal with limitations and limitation types.
 *
 * @internal Meant for internal use by Repository.
 */
class LimitationService
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     */
    public function __construct(array $settings = array())
    {
        // Union makes sure default settings are ignored if provided in argument
        $this->settings = $settings + array('limitationTypes' => array());
    }

    /**
     * Returns the LimitationType registered with the given identifier.
     *
     * Returns the correct implementation of API Limitation value object
     * based on provided identifier
     *
     * @param string $identifier
     *
     * @throws \Eki\NRW\Component\Core\Base\Exceptions\NotFound\LimitationNotFoundException
     *
     * @return \Eki\NRW\Component\Base\Permission\Role\Limitation\Type
     */
    public function getLimitationType($identifier)
    {
        if (!isset($this->settings['limitationTypes'][$identifier])) {
            throw new LimitationNotFoundException($identifier);
        }

        return $this->settings['limitationTypes'][$identifier];
    }

    /**
     * Validates an array of Limitations.
     *
     * @uses ::validateLimitation()
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\User\Limitation[] $limitations
     *
     * @return \Eki\NRW\Component\Core\Values\ValidationError[][]
     */
    public function validateLimitations(array $limitations)
    {
        $allErrors = array();
        foreach ($limitations as $limitation) {
            $errors = $this->validateLimitation($limitation);
            if (!empty($errors)) {
                $allErrors[$limitation->getIdentifier()] = $errors;
            }
        }

        return $allErrors;
    }

    /**
     * Validates single Limitation.
     *
     * @throws \Eki\NRW\Component\Core\Base\Exceptions\BadStateException If the Role settings is in a bad state
     *
     * @param \Eki\NRW\Component\Base\Permission\Role\Limitation $limitation
     *
     * @return \Eki\NRW\Component\Core\Values\ValidationError[]
     */
    public function validateLimitation(Limitation $limitation)
    {
        $identifier = $limitation->getIdentifier();
        if (!isset($this->settings['limitationTypes'][$identifier])) {
            throw new BadStateException(
                '$identifier',
                "limitationType[{$identifier}] is not configured"
            );
        }

        /**
         * @var \Eki\NRW\Component\SPBase\Permission\Role\Limitation\Type
         */
        $type = $this->settings['limitationTypes'][$identifier];

        // This will throw if it does not pass
        $type->acceptValue($limitation);

        // This return array of validation errors
        return $type->validate($limitation);
    }
}
