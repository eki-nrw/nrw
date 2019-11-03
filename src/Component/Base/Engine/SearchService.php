<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

use Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion;
use Eki\NRW\Component\Base\Engine\Values\Subject\Query;

/**
 * Search service.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface SearchService
{
    /**
     * Finds subject objects for the given query.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if query is not valid
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\Subject\Query $query
     * @param array $languageFilter Configuration for specifying prioritized languages query will be performed on.
     *        Also used to define which field languages are loaded for the returned subject.
     *        Currently supports: <code>array("languages" => array(<language1>,..), "useAlwaysAvailable" => bool)</code>
     *                            useAlwaysAvailable defaults to true to avoid exceptions on missing translations
     * @param bool $filterOnUserPermissions if true only the objects which the user is allowed to read are returned.
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Subject\Search\SearchResult
     */
    public function findSubject(Query $query, array $languageFilter = array(), $filterOnUserPermissions = true);

    /**
     * Performs a query for a single subject object.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException if the object was not found by the query or due to permissions
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if criterion is not valid
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if there is more than than one result matching the criterions
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion $filter
     * @param array $languageFilter Configuration for specifying prioritized languages query will be performed on.
     *        Currently supports: <code>array("languages" => array(<language1>,..), "useAlwaysAvailable" => bool)</code>
     *                            useAlwaysAvailable defaults to true to avoid exceptions on missing translations
     * @param bool $filterOnUserPermissions if true only the objects which is the user allowed to read are returned.
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Subject\Subject
     */
    public function findSingle(Criterion $filter, array $languageFilter = array(), $filterOnUserPermissions = true);

    /**
     * Suggests a list of values for the given prefix.
     *
     * @param string $prefix
     * @param string[] $fieldPaths
     * @param int $limit
     * @param \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion $filter
     */
    public function suggest($prefix, $fieldPaths = array(), $limit = 10, Criterion $filter = null);

}
