<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Base;

/**
 * Trait providing a default implementation of Translatable.
 */
trait TranslatableBase
{
    private $messageTemplate;

    private $parameters = [];

    public function setMessageTemplate($messageTemplate)
    {
        $this->messageTemplate = $messageTemplate;
    }

    public function getMessageTemplate()
    {
        return $this->messageTemplate;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function addParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function addParameters(array $parameters)
    {
        $this->parameters += $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getBaseTranslation()
    {
        return strtr($this->messageTemplate, $this->parameters);
    }
}
