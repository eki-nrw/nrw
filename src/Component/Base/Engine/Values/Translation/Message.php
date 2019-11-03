<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Values\Translation;

use Eki\NRW\Component\Base\Engine\Values\Translation;

/**
 * Class for translatable messages, which only occur in singular form.
 *
 * The message might include replacements, in the form %[A-Za-z]%. Those are
 * replaced by the values provided. A raw % can be escaped like %%.
 */
class Message extends Translation
{
    /**
     * Message string. Might use replacements like %foo%, which are replaced by
     * the values specified in the values array.
     *
     * @var string
     */
    protected $message;

    /**
     * Translation value objects. May not contain any numbers, which might
     * result in requiring plural forms. Use Plural for that.
     *
     * @var array
     */
    protected $values;

    /**
     * Construct singular only message from string and optional value array.
     *
     * @param string $message
     * @param array $values
     */
    public function __construct($message, array $values = array())
    {
        $this->message = $message;
        $this->values = $values;
    }
}
