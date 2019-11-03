<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Values\Subject;

use Eki\NRW\Component\Base\Engine\Values\ValueObject;

/**
 * This class is used to perform a Content query.
 */
class Query extends ValueObject
{
    const SORT_ASC = 'ascending';

    const SORT_DESC = 'descending';

    /**
     * The Query filter.
     *
     * For the storage backend that supports it (Solr) filters the result set
     * without influencing score. It also offers better performance as filter
     * part of the Query can be cached.
     *
     * In case when the backend does not distinguish between query and filter
     * (Legacy Storage implementation), it will simply be combined with Query query
     * using LogicalAnd criterion.
     *
     * Can contain multiple criterion, as items of a logical one (by default
     * AND)
     *
     * @var \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion
     */
    public $filter;

    /**
     * The Query query.
     *
     * For the storage backend that supports it (Solr Storage) query will influence
     * score of the search results.
     *
     * Can contain multiple criterion, as items of a logical one (by default
     * AND). Defaults to MatchAll.
     *
     * @var \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion
     */
    public $query;

    /**
     * Query sorting clauses.
     *
     * @var \Eki\NRW\Component\Base\Engine\Values\Subject\Query\SortClause[]
     */
    public $sortClauses = array();

    /**
     * An array of facet builders.
     *
     * Search engines may ignore any, or given facet builders they don't support and will just return search result
     * facets supported by the engine. API consumer should dynamically iterate over returned facets for further use.
     *
     * @var \Eki\NRW\Component\Base\Engine\Values\Subject\Query\FacetBuilder[]
     */
    public $facetBuilders = array();

    /**
     * Query offset.
     *
     * Sets the offset for search hits, used for paging the results.
     *
     * @var int
     */
    public $offset = 0;

    /**
     * Query limit.
     *
     * Limit for number of search hits to return.
     * If value is `0`, search query will not return any search hits, useful for doing a count.
     *
     * @var int
     */
    public $limit = 25;

    /**
     * If true spellcheck suggestions are returned.
     *
     * @var bool
     */
    public $spellcheck;

    /**
     * If true, search engine should perform count even if that means extra lookup.
     *
     * @var bool
     */
    public $performCount = true;
}
