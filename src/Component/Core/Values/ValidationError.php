<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Values;

use Eki\NRW\Component\SPBase\Values\ValidationError as ValidationErrorInterface;
use Eki\NRW\Component\Base\Engine\Values\Translation\Message;
use Eki\NRW\Component\Base\Engine\Values\Translation\Plural;

/**
 * Class for validation errors.
 */
class ValidationError implements ValidationErrorInterface
{
    /**
     * @var string
     */
    protected $singular;

    /**
     * @var string
     */
    protected $plural;

    /**
     * @var array
     */
    protected $values;

    /**
     * Element on which the error occurred
     * e.g. property name or property path compatible with Symfony PropertyAccess component.
     *
     * Example: StringLengthValidator[minStringLength]
     *
     * @var string
     */
    protected $target;

    /**
     * @param string $singular
     * @param string $plural
     * @param array $values
     */
    public function __construct($singular, $plural = null, array $values = array(), $target = null)
    {
        $this->singular = $singular;
        $this->plural = $plural;
        $this->values = $values;
        $this->target = $target;
    }

    /**
     * Returns a translatable Message.
     *
     * @return \Eki\NRW\Component\Base\Emgine\Values\Translation
     */
    public function getTranslatableMessage()
    {
        if (isset($this->plural)) {
            return new Plural(
                $this->singular,
                $this->plural,
                $this->values
            );
        } else {
            return new Message(
                $this->singular,
                $this->values
            );
        }
    }

    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function getTarget()
    {
        return $this->target;
    }
}
