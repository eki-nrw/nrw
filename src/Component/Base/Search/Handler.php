<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Search;

use Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion;
use Eki\NRW\Component\Base\Engine\Values\Subject\Query;

/**
 * The Search handler retrieves sets of of Subject objects, based on a
 * set of criteria.
 */
interface Handler
{
    /**
     * Finds subject objects for the given query.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if Query criterion is not applicable to its target
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\Subject\Query $query
     * @param array $languageFilter - a map of language related filters specifying languages query will be performed on.
     *        Also used to define which field languages are loaded for the returned subject.
     *        Currently supports: <code>array("languages" => array(<language1>,..), "useAlwaysAvailable" => bool)</code>
     *                            useAlwaysAvailable defaults to true to avoid exceptions on missing translations
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Subject\Search\SearchResult With SubjectInfo as SearchHit->valueObject
     */
    public function findSubject(Query $query, array $languageFilter = array());

    /**
     * Performs a query for a single subject object.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotFoundException if the object was not found by the query or due to permissions
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if Criterion is not applicable to its target
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\InvalidArgumentException if there is more than than one result matching the criterions
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion $filter
     * @param array $languageFilter - a map of language related filters specifying languages query will be performed on.
     *        Also used to define which field languages are loaded for the returned subject.
     *        Currently supports: <code>array("languages" => array(<language1>,..), "useAlwaysAvailable" => bool)</code>
     *                            useAlwaysAvailable defaults to true to avoid exceptions on missing translations
     *
     * @return \eZ\Publish\SPI\Persistence\Subject\SubjectInfo
     */
    public function findSingle(Criterion $filter, array $languageFilter = array());

    /**
     * Suggests a list of values for the given prefix.
     *
     * @param string $prefix
     * @param string[] $fieldPaths
     * @param int $limit
     * @param \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion $filter
     */
    public function suggest($prefix, $fieldPaths = array(), $limit = 10, Criterion $filter = null);

    /**
     * Indexes a subject object.
     *
     * @param object $subject
     */
    public function indexSubject($subject);

    /**
     * Deletes a subject object from the index.
     *
     * @param int $subjectId
     */
    public function deleteSubject($subjectId);

    /**
     * Purges all subjects from the index.
     */
    public function purgeIndex();
}
