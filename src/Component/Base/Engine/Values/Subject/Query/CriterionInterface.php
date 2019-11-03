<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Values\Subject\Query;

/**
 * Base interface for Criterion implementations.
 */
interface CriterionInterface
{
    /**
     * Creates a new Criterion for $target with operator $operator on $value.
     *
     * @param string $target The target (field identifier for a field, metadata identifier, etc)
     * @param string $operator The criterion operator, from Criterion\Operator
     * @param mixed $value The Criterion value, either as an individual item or an array
     *
     *@return CriterionInterface
     */
    public static function createFromQueryBuilder($target, $operator, $value);

    /**
     * Criterion description function.
     *
     * Returns the combination of the Criterion's supported operator/value,
     * as an array of Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion\Operator\Specifications objects
     * - Operator is one supported Operator, as an Operator::* constant
     * - ValueType is the type of input value this operator requires, either array or single
     * - SupportedTypes is an array of types the operator will accept
     * - ValueCountLimitation is an integer saying how many values are expected.
     *
     * <code>
     * // IN and EQ are supported
     * return array(
     *     // The EQ operator expects a single value, either as an integer or a string
     *     new Specifications(
     *         Operator::EQ,
     *         Specifications::INPUT_TYPE_SINGLE,
     *         array( Specifications::INPUT_VALUE_INTEGER, Specifications::INPUT_VALUE_STRING ),
     *     ),
     *     // The IN operator expects an array of values, of either integers or strings
     *     new Specifications(
     *         Operator::IN,
     *         Specifications::INPUT_TYPE_ARRAY,
     *         array( Specifications::INPUT_VALUE_INTEGER, Specifications::INPUT_VALUE_STRING )
     *     )
     * )*
     * </code>
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion\Operator\Specifications[]
     */
    public function getSpecifications();
}
