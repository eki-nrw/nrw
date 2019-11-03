<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Search;

use Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion;
use Eki\NRW\Component\Base\Engine\Values\Subject\Query;

/**
* Search Handler interface 
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
interface Handler
{
    /**
     * Performs a query for a single subject object.
     *
     * @throws \Eki\NRW\Component\Base\Exceptions\NotFoundException if the object was not found by the query or due to permissions
     * @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException if Criterion is not applicable to its target
     * @throws \Eki\NRW\Component\Base\Exceptions\InvalidArgumentException if there is more than than one result matching the criterions
     *
     * @param \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion $filter
     * @param array $languageFilter - a map of language related filters specifying languages query will be performed on.
     *        Also used to define which field languages are loaded for the returned content.
     *        Currently supports: <code>array("languages" => array(<language1>,..), "useAlwaysAvailable" => bool)</code>
     *                            useAlwaysAvailable defaults to true to avoid exceptions on missing translations
     *
     * @return \Eki\NRW\Component\SPBase\Persistence\Subject\SubjectInfo
     */
    public function findSingle(Criterion $filter, array $languageFilter = array());

    /**
     * Suggests a list of values for the given prefix.
     *
     * @param string $prefix
     * @param string[] $paths
     * @param int $limit
     * @param \Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion $filter
     */
    public function suggest($prefix, $paths = array(), $limit = 10, Criterion $filter = null);
	
    /**
     * Indexes a subject in the index storage.
     *
     * @param \Eki\Component\SPBase\Persistence\Subject\Subject $subject
     */
    public function indexSubject(Subject $subject);

    /**
     * Deletes a subject from the index.
     *
     * @param mixed $subjectId
     */
    public function deleteSubject($subjectId);
	
    /**
     * Purges all contents from the index.
     */
    public function purgeIndex();
}
