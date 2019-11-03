<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Permission;

/**
 * This service provides methods for resolving criterion permissions.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
interface PermissionCriterionResolver
{
    /**
     * Get criteria representation for a permission.
     *
     * Will return a criteria if current user has limited access to the given service/function,
     * however if user has either full or no access then boolean is returned.
     *
     * @param string $service
     * @param string $function
     *
     * @return bool|\Eki\NRW\Component\Base\Engine\Values\Subject\Query\Criterion
     */
    public function getPermissionsCriterion($service, $function);
}
