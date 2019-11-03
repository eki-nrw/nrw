<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Permission\Limitation;

use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation as LimitationValue;
use Eki\NRW\Component\Base\Engine\Permission\User\UserReference;

/**
 * This interface represent the Limitation Type.
 *
 * A Limitation is a lot like a Symfony voter, telling the permission system if user has
 * access or not. It consists of a Limitation Value which is persisted, and this Limitation
 * Type which contains the business logic for evaluate ("vote"), as well as accepting and
 * validating the Value object and to generate criteria for content/location searches.
 */
interface Type
{
    /**
     * Constants for return value of {@see evaluate()}.
     *
     * Currently ACCESS_ABSTAIN must mean that evaluate does not support the provided $object or $targets,
     * this is currently only supported by role limitations as policy limitations should not allow this.
     *
     * Note: In future version constant values might change to 1, 0 and -1 as used in Symfony.
     *
     */
    const ACCESS_GRANTED = true;
    const ACCESS_ABSTAIN = null;
    const ACCESS_DENIED = false;

    /**
     * Constants for valueSchema() return values.
     *
     * Used in cases where a certain value is accepted but the options are to many to return as a hash of options.
     * GUI should typically present option to browse content tree to select limitation value(s).
     */
    const VALUE_SCHEMA_LOCATION_ID = 1;
    const VALUE_SCHEMA_LOCATION_PATH = 2;

    /**
     * Accepts a Limitation value and checks for structural validity.
     *
     * Makes sure LimitationValue object and ->limitationValues is of correct type.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException If the value does not match the expected type/structure
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation $limitationValue
     */
    public function acceptValue(LimitationValue $limitationValue);

    /**
     * Create the Limitation Value.
     *
     * The is the method to create values as Limitation type needs value knowledge anyway in acceptValue,
     * the reverse relation is provided by means of identifier lookup (Value has identifier, and so does RoleService).
     *
     * @param mixed[] $limitationValues
     *
     * @return \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation
     */
    public function buildValue(array $limitationValues);

    /**
     * Evaluate ("Vote") against a main value object and targets for the context.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException If any of the arguments are invalid
     *         Example: If LimitationValue is instance of ContentTypeLimitationValue, and Type is SectionLimitationType.
     *         However if $object or $targets is unsupported by ROLE limitation, ACCESS_ABSTAIN should be returned!
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\BadStateException If value of the LimitationValue is unsupported
     *         Example if OwnerLimitationValue->limitationValues[0] is not one of: [ 1,  2 ]
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation $value
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\UserReference $currentUser
     * @param object $object
     * @param object[]|null $targets An array of location, parent or "assignment" objects, if null: none where provided by caller
     *
     * @return bool|null Returns one of ACCESS_* constants
     */
    public function evaluate(LimitationValue $value, UserReference $currentUser, $object, array $targets = null);

    /**
     * Makes sure LimitationValue->limitationValues is valid according to valueSchema().
     *
     * Make sure {@link acceptValue()} is checked first!
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation $limitationValue
     *
     * @return \Eki\NRW\Component\SPBase\Values\ValidationError[]
     */
    public function validate(LimitationValue $limitationValue);
}
