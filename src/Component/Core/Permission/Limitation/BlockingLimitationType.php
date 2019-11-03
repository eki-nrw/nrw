<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Permission\Limitation\Limitation;

use Eki\NRW\Component\Base\Engine\Exceptions\NotImplementedException;
use Eki\NRW\Component\Base\Engine\Permission\User\UserReference;
use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation as LimitationValue;
use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation\BlockingLimitation;
use Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentType;

use Eki\NRW\Component\Core\Persistence\ValidationError;

use Eki\NRW\Component\SPBase\Permission\Role\Limitation\Type as LimitationTypeInterface;

/**
 * BlockingLimitationType is a limitation type that always says no to the permission system.
 *
 * It is for use in cases where a limitation is not implemented, or limitation is legacy specific
 * and it is then not possible to know when to say yes, so we need to say no.
 */
class BlockingLimitationType implements LimitationTypeInterface
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * Create new Blocking Limitation with identifier injected dynamically.
     *
     * @throws \InvalidArgumentException If $identifier is empty
     *
     * @param string $identifier The identifier of the limitation
     */
    public function __construct($identifier)
    {
        if (empty($identifier)) {
            throw new \InvalidArgumentException('Argument $identifier can not be empty');
        }

        $this->identifier = $identifier;
    }

    /**
     * Accepts a Blocking Limitation value and checks for structural validity.
     *
     * Makes sure LimitationValue object and ->limitationValues is of correct type.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException If the value does not match the expected type/structure
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\User\Limitation $limitationValue
     */
    public function acceptValue(LimitationValue $limitationValue)
    {
        if (!$limitationValue instanceof BlockingLimitation) {
            throw new InvalidArgumentType('$limitationValue', 'BlockingLimitation', $limitationValue);
        } elseif (!is_array($limitationValue->limitationValues)) {
            throw new InvalidArgumentType('$limitationValue->limitationValues', 'array', $limitationValue->limitationValues);
        }
    }

    /**
     * Makes sure LimitationValue->limitationValues is valid according to valueSchema().
     *
     * Make sure {@link acceptValue()} is checked first!
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\User\Limitation $limitationValue
     *
     * @return \Eki\NRW\Component\Core\Persistence\ValidationError[]
     */
    public function validate(LimitationValue $limitationValue)
    {
        $validationErrors = array();
        if (empty($limitationValue->limitationValues)) {
            $validationErrors[] = new ValidationError(
                "\$limitationValue->limitationValues => '%value%' can not be empty",
                null,
                array(
                    'value' => $limitationValue->limitationValues,
                )
            );
        }

        return $validationErrors;
    }

    /**
     * Create the Limitation Value.
     *
     * @param mixed[] $limitationValues
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\User\Limitation
     */
    public function buildValue(array $limitationValues)
    {
        return new BlockingLimitation($this->identifier, $limitationValues);
    }

    /**
     * Evaluate permission against content & target(placement/parent/assignment).
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException If any of the arguments are invalid
     *         Example: If LimitationValue is instance of ContentTypeLimitationValue, and Type is SectionLimitationType.
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\BadStateException If value of the LimitationValue is unsupported
     *         Example if OwnerLimitationValue->limitationValues[0] is not one of: [ 1,  2 ]
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\User\Limitation $value
     * @param \Eki\NRW\Component\Base\Permission\User\UserReference $currentUser
     * @param object $object
     * @param \Eki\NRW\Component\Base\Engine\Values\ValueObject[]|null $targets The context of the $object, like Location of Content, if null none where provided by caller
     *
     * @return bool
     */
    public function evaluate(LimitationValue $value, UserReference $currentUser, $object, array $targets = null)
    {
        if (!$value instanceof BlockingLimitation) {
            throw new InvalidArgumentException('$value', 'Must be of type: BlockingLimitation');
        }

        return false;
    }
}
