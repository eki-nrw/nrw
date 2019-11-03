<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Values\Subject\Search;

use Eki\NRW\Component\Base\Engine\Values\ValueObject;

/**
 * This class represents a search result.
 */
class SearchResult extends ValueObject
{
    /**
     * The facets for this search.
     *
     * @var \Eki\NRW\Component\Base\Engine\Values\Subject\Search\Facet[]
     */
    public $facets = array();

    /**
     * The value objects found for the query.
     *
     * @var \Eki\NRW\Component\Base\Engine\Values\Subject\Search\SearchHit[]
     */
    public $searchHits = array();

    /**
     * If spellcheck is on this field contains a collated query suggestion where in the appropriate
     * criterions the wrong spelled value is replaced by a corrected one (TBD).
     *
     * @var \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion
     */
    public $spellSuggestion;

    /**
     * The duration of the search processing in ms.
     *
     * @var int
     */
    public $time;

    /**
     * Indicates if the search has timed out.
     *
     * @var bool
     */
    public $timedOut;

    /**
     * The maximum score of this query.
     *
     * @var float
     */
    public $maxScore;

    /**
     * The total number of searchHits.
     *
     * `null` if Query->performCount was set to false and search engine avoids search lookup.
     *
     * @var int|null
     */
    public $totalCount;
}
