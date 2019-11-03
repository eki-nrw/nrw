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

use Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException as BaseNotFoundException;
//use Eki\NRW\Component\Base\Engine\Values\ValueObject;
use Eki\NRW\Component\Base\Engine\Permission\User\UserReference as BaseUserReference;

use Eki\NRW\Component\Base\Engine\Values\Content\Content;
use Eki\NRW\Component\Base\Engine\Values\Content\ContentInfo;
use Eki\NRW\Component\Base\Engine\Values\Content\ContentCreateStruct;
use Eki\NRW\Component\Base\Engine\Values\Content\VersionInfo;

use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentType;

use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation\LanguageLimitation as BaseLanguageLimitation;
use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation as BaseLimitationValue;
use Eki\NRW\Component\SPBase\Permission\Role\Limitation\Type as LimitationTypeInterface;
use Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion;

use Eki\NRW\Component\Core\FieldType\ValidationError;

/**
 * LanguageLimitation is a Content limitation.
 */
class LanguageLimitationType extends AbstractPersistenceLimitationType implements LimitationTypeInterface
{
    /**
     * Accepts a Limitation value and checks for structural validity.
     *
     * Makes sure LimitationValue object and ->limitationValues is of correct type.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException If the value does not match the expected type/structure
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\Limitation $limitationValue
     */
    public function acceptValue(BaseLimitationValue $limitationValue)
    {
        if (!$limitationValue instanceof BaseLanguageLimitation) {
            throw new InvalidArgumentType('$limitationValue', 'BaseLanguageLimitation', $limitationValue);
        } elseif (!is_array($limitationValue->limitationValues)) {
            throw new InvalidArgumentType('$limitationValue->limitationValues', 'array', $limitationValue->limitationValues);
        }

        foreach ($limitationValue->limitationValues as $key => $value) {
            if (!is_string($value)) {
                throw new InvalidArgumentType("\$limitationValue->limitationValues[{$key}]", 'string', $value);
            }
        }
    }

    /**
     * Makes sure LimitationValue->limitationValues is valid according to valueSchema().
     *
     * Make sure {@link acceptValue()} is checked first!
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation $limitationValue
     *
     * @return \eZ\Publish\SPI\FieldType\ValidationError[]
     */
    public function validate(BaseLimitationValue $limitationValue)
    {
        $validationErrors = array();
        foreach ($limitationValue->limitationValues as $key => $value) {
            try {
                $this->persistence->contentLanguageHandler()->loadByLanguageCode($value);
            } catch (BaseNotFoundException $e) {
                $validationErrors[] = new ValidationError(
                    "limitationValues[%key%] => '%value%' does not exist in the backend",
                    null,
                    array(
                        'value' => $value,
                        'key' => $key,
                    )
                );
            }
        }

        return $validationErrors;
    }

    /**
     * Create the Limitation Value.
     *
     * @param mixed[] $limitationValues
     *
     * @return \Eki\NRW\Component\Base\Engine\Permission\User\Limitation
     */
    public function buildValue(array $limitationValues)
    {
        return new APILanguageLimitation(array('limitationValues' => $limitationValues));
    }

    /**
     * Evaluate permission against content & target(placement/parent/assignment).
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException If any of the arguments are invalid
     *         Example: If LimitationValue is instance of ContentTypeLimitationValue, and Type is SectionLimitationType.
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\BadStateException If value of the LimitationValue is unsupported
     *         Example if OwnerLimitationValue->limitationValues[0] is not one of: [ 1,  2 ]
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\Limitation $value
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\UserReference $currentUser
     * @param \Eki\NRW\Component\Base\Engine\Values\ValueObject $object
     * @param \Eki\NRW\Component\Base\Engine\Values\ValueObject[]|null $targets The context of the $object, like Location of Content, if null none where provided by caller
     *
     * @return bool
     */
    public function evaluate(BaseLimitationValue $value, BaseUserReference $currentUser, ValueObject $object, array $targets = null)
    {
        if (!$value instanceof APILanguageLimitation) {
            throw new InvalidArgumentException('$value', 'Must be of type: APILanguageLimitation');
        }

        if ($object instanceof Content) {
            $object = $object->getVersionInfo();
        } elseif (!$object instanceof VersionInfo && !$object instanceof ContentInfo && !$object instanceof ContentCreateStruct) {
            throw new InvalidArgumentException(
                '$object',
                'Must be of type: ContentCreateStruct, Content, VersionInfo or ContentInfo'
            );
        }

        if (empty($value->limitationValues)) {
            return false;
        }

        if ($object instanceof ContentInfo || $object instanceof ContentCreateStruct) {
            return in_array($object->mainLanguageCode, $value->limitationValues, true);
        }

        /*
         * @var $object VersionInfo
         */
        foreach ($value->limitationValues as $limitationLanguageCode) {
            if ($object->initialLanguageCode === $limitationLanguageCode) {
                return true;
            }
            if (in_array($limitationLanguageCode, $object->languageCodes, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns Criterion for use in find() query.
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\Limitation $value
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\UserReference $currentUser
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Subject|Query\CriterionInterface
     */
    public function getCriterion(BaseLimitationValue $value, BaseUserReference $currentUser)
    {
        if (empty($value->limitationValues)) {
            // no limitation values
            throw new \RuntimeException('$value->limitationValues is empty, it should not have been stored in the first place');
        }

        if (!isset($value->limitationValues[1])) {
            // 1 limitation value: EQ operation
            return new Criterion\LanguageCode($value->limitationValues[0]);
        }

        // several limitation values: IN operation
        return new Criterion\LanguageCode($value->limitationValues);
    }
}
