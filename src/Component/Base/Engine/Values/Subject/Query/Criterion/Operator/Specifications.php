<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion\Operator;

/**
 * This class is used by Criteria to describe which operators they support.
 *
 * Instances of this class are returned in an array by the {@see Criterion::getSpecifications()} method
 */
class Specifications
{
    /**
     * Criterion input type description constants.
     */
    const FORMAT_SINGLE = 'single';
    const FORMAT_ARRAY = 'array';

    /**
     * Criterion input value type description constants.
     * Used by {@see getDescription()} to say which type of values an operator expects.
     */
    const TYPE_INTEGER = 1;
    const TYPE_STRING = 2;
    const TYPE_BOOLEAN = 4;

    /**
     * Specified operator, as one of the Operator::* constants.
     */
    public $operator;

    /**
     * Format supported for the Criterion value, either {@see self::FORMAT_SINGLE} for single
     * or {@see self::FORMAT_ARRAY} for multiple.
     *
     * @see self::FORMAT_*
     *
     * @var string
     */
    public $valueFormat;

    /**
     * Accepted values types, specifying what type of variables are accepted as a value.
     *
     * @see self::TYPE_*
     *
     * @var int
     */
    public $valueTypes;

    /**
     * Limitation on the number of items as the value.
     *
     * Only usable if {@see $valueFormat} is {@see self::FORMAT_ARRAY}.
     * Not setting it means that 1...n will be required
     *
     * @var int
     */
    public $valueCount;

    /**
     * Creates a new Specifications object.
     *
     * @param string $operator The specified operator, as one of the Operator::* constants
     * @param string $valueFormat The accepted value format, either {@see self::FORMAT_ARRAY} or {@see self::FORMAT_SINGLE}
     * @param int $valueTypes The supported value types, as a bit field of the {@see self::TYPE_*} constants
     * @param int $valueCount The required number of values, when the accepted format is {@see self::FORMAT_ARRAY}
     */
    public function __construct($operator, $valueFormat, $valueTypes = null, $valueCount = null)
    {
        $this->operator = $operator;
        $this->valueFormat = $valueFormat;
        $this->valueTypes = $valueTypes;
        $this->valueCount = $valueCount;
    }
}
