<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Values\Subject\Search\Facet;

use Eki\NRW\Component\Base\Engine\Values\Subject\Search\Facet;

/**
 * This class holds the count of content matching the criterion.
 */
class CriterionFacet extends Facet
{
    /**
     * The count of objects matching the criterion.
     *
     * @var int
     */
    public $count;
}
