<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\HttpUtils as BaseHttpUtils;

class HttpUtils extends BaseHttpUtils
{
    private function analyzeLink($path)
    {
        return $path;
    }

    public function generateUri($request, $path)
    {
        if ($this->isRouteName($path)) {
            // Remove siteaccess attribute to avoid triggering reverse siteaccess lookup during link generation.
            $request->attributes->remove('siteaccess');
        }

        return parent::generateUri($request, $this->analyzeLink($path));
    }

    public function checkRequestPath(Request $request, $path)
    {
        return parent::checkRequestPath($request, $this->analyzeLink($path));
    }

    /**
     * @param string $path Path can be URI, absolute URL or a route name.
     *
     * @return bool
     */
    private function isRouteName($path)
    {
        return $path && strpos($path, 'http') !== 0 && strpos($path, '/') !== 0;
    }
}
