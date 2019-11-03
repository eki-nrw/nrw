<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Bundle\CoreBundle\DependencyInjection\Configuration\ContextAccessAware;

/**
 * Interface for dynamic setting parsers.
 * A dynamic setting is a string representation of a ConfigResolver::getParameter() call.
 * It allows usage of the ConfigResolver from e.g. configuration files.
 *
 * Supported syntax for dynamic settings: $<paramName>[;<namespace>[;<scope>]]$
 *
 * The following will work :
 * $my_param$ (using default namespace, e.g. ezsettings, with current scope).
 * $my_param;foo$ (using "foo" as namespace, in current scope).
 * $my_param;foo;some_contextaccess$ (using "foo" as namespace, forcing "some_contextaccess scope").
 *
 * $my_param$ is the equivalent of $configResolver->getParameter( 'my_param' );
 * $my_param;foo$ is the equivalent of $configResolver->getParameter( 'my_param', 'foo' );
 * $my_param;foo;some_contextaccess$ is the equivalent of $configResolver->getParameter( 'my_param', 'foo', 'some_contextaccess' );
 */
interface DynamicSettingParserInterface
{
    const BOUNDARY_DELIMITER = '$';
    const INNER_DELIMITER = ';';

    /**
     * Checks if $setting is considered to be dynamic.
     * i.e. if $setting follows the expected format.
     *
     * @param string $setting
     *
     * @return bool
     */
    public function isDynamicSetting($setting);

    /**
     * Parses $setting and returns a hash of corresponding arguments.
     * Returned hash will contain the following entries:.
     *
     * - "param": the parameter name (e.g. "my_param").
     * - "namespace": the namespace. Will be null if none was specified (considered default).
     * - "scope": the scope. Will be null if none was specified (considered default).
     *
     * @param string $setting
     *
     * @return array
     */
    public function parseDynamicSetting($setting);
}
