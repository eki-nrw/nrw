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
 * This class represents a SearchHit matching the query.
 */
class SearchHit extends ValueObject
{
    /**
     * The value found by the search.
     *
     * @var object
     */
    public $object;

    /**
     * The score of this value;.
     *
     * @var float
     */
    public $score;

    /**
     * The index identifier where this value was found.
     *
     * @var string
     */
    public $index;

    /**
     * Language code of the Content translation that matched the query.
     *
     * @var string
     */
    public $matchedTranslation;

    /**
     * A representation of the search hit including highlighted terms.
     *
     * @var string
     */
    public $highlight;
}
