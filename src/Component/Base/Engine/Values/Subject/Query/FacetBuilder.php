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

use Eki\NRW\Component\Base\Engine\Values\ValueObject;

/**
 * This class is the base class for facet builders.
 */
abstract class FacetBuilder extends ValueObject
{
    /**
     * The name of the facet.
     *
     * @var string
     */
    public $name;

    /**
     * If true the facet runs in a global mode not restricted by the query.
     *
     * @var bool
     */
    public $global = false;

    /**
     * An additional facet filter that will further filter the documents the facet will be executed on.
     *
     * @var \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion
     */
    public $filter = null;

    /**
     * Number of facets (terms) returned.
     *
     * @var int
     */
    public $limit = 10;

    /**
     * Specifies the minimum count. Only facet groups with more or equal results are returned.
     *
     * @var int
     */
    public $minCount = 1;
}
