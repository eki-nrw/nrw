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

use OutOfBoundsException;

class DynamicSettingParser implements DynamicSettingParserInterface
{
    public function isDynamicSetting($setting)
    {
        // Checks if $setting begins and ends with appropriate delimiter.
        return
            is_string($setting)
            && strpos($setting, static::BOUNDARY_DELIMITER) === 0
            && substr($setting, -1) === static::BOUNDARY_DELIMITER
            && substr_count($setting, static::BOUNDARY_DELIMITER) == 2
            && substr_count($setting, static::INNER_DELIMITER) <= 2;
    }

    public function parseDynamicSetting($setting)
    {
        $params = explode(static::INNER_DELIMITER, $this->removeBoundaryDelimiter($setting));
        if (count($params) > 3) {
            throw new OutOfBoundsException('Dynamic settings cannot have more than 3 segments: $paramName;namespace;scope$');
        }

        return array(
            'param' => $params[0],
            'namespace' => isset($params[1]) ? $params[1] : null,
            'scope' => isset($params[2]) ? $params[2] : null,
        );
    }

    /**
     * @param string $setting
     *
     * @return string
     */
    private function removeBoundaryDelimiter($setting)
    {
        return substr($setting, 1, -1);
    }
}
