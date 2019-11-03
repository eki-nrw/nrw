<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Common\Configuration;

use Eki\NRW\Mdl\Contexture\Configuration\ConfigResolverInterface as BaseConfigResolverInterface;

/**
 * Interface for config resolvers.
 *
 * Classes implementing this interface will help you get settings for a specific scope.
 * In Eki NRW context, this is useful to get a setting for a specific siteaccess for example.
 *
 * The idea is to check the different scopes available for a given namespace to find the appropriate parameter.
 * To work, the dynamic setting must comply internally to the following name format : "<namespace>.<scope>.parameter.name".
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 */
interface ConfigResolverInterface extends BaseConfigResolverInterface
{
}
