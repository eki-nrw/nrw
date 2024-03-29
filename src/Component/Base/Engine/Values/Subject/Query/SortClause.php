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

use Eki\NRW\Component\Base\Engine\Values\Subject\Query;
use InvalidArgumentException;

/**
 * This class is the base for SortClause classes, used to set sorting of content queries.
 */
abstract class SortClause
{
    /**
     * Sort direction
     * One of Query::SORT_ASC or Query::SORT_DESC;.
     *
     * @var string
     */
    public $direction = Query::SORT_ASC;

    /**
     * Sort target, high level: section_identifier, attribute_value, etc.
     *
     * @var string
     */
    public $target;

    /**
     * Extra target data, required by some sort clauses, field for instance.
     *
     * @var SortClause\Target
     */
    public $targetData;

    /**
     * Constructs a new SortClause on $sortTarget in direction $sortDirection.
     *
     * @param string $sortTarget
     * @param string $sortDirection one of Query::SORT_ASC or Query::SORT_DESC
     * @param string $targetData Extra target data, used by some clauses (field for instance)
     *
     * @throws InvalidArgumentException if the given sort order isn't one of Query::SORT_ASC or Query::SORT_DESC
     */
    public function __construct($sortTarget, $sortDirection, $targetData = null)
    {
        if ($sortDirection !== Query::SORT_ASC && $sortDirection !== Query::SORT_DESC) {
            throw new InvalidArgumentException('Sort direction must be one of Query::SORT_ASC or Query::SORT_DESC');
        }

        $this->direction = $sortDirection;
        $this->target = $sortTarget;

        if ($targetData !== null) {
            $this->targetData = $targetData;
        }
    }
}
