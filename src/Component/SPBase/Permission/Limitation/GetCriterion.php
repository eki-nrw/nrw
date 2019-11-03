<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\SPBase\Permission\Limitation;

use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation as LimitationValue;
use Eki\NRW\Component\Base\Engine\Permission\User\UserReference;

/**
 * 
 */
interface GetCriterion
{
    /**
     * Returns Criterion for use in find() query.
     *
     * @throws \Eki\NRW\Component\Base\Engine\Exceptions\NotImplementedException If the limitation does not support
     *         being used as a Criterion.
     *
     * @param \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation $value
     * @param \Eki\NRW\Component\Base\Engine\Permission\User\UserReference $currentUser
     *
     * @return \Eki\NRW\Component\Base\Engine\Values\Subject\Query\CriterionInterface
     */
    public function getCriterion(LimitationValue $value, UserReference $currentUser);
}
