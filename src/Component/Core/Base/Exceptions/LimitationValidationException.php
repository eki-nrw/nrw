<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Base\Exceptions;

use Eki\NRW\Components\Base\Exceptions\LimitationValidationException as APILimitationValidationException;
use Eki\NRW\Components\Core\Base\Translatable;
use Eki\NRW\Components\Core\Base\TranslatableBase;

/**
 * This Exception is thrown on create, update or assign policy or role
 * when one or more given limitations are not valid.
 */
class LimitationValidationException extends APILimitationValidationException implements Translatable
{
    use TranslatableBase;

    /**
     * Contains an array of limitation ValidationError objects.
     *
     * @var \Eki\NRW\Component\Core\FieldType\ValidationError[]
     */
    protected $errors;

    /**
     * Generates: Limitations did not validate.
     *
     * Also sets the given $errors to the internal property, retrievable by getValidationErrors()
     *
     * @param \Eki\NRW\Component\Core\FieldType\ValidationError[] $errors
     */
    public function __construct(array $errors)
    {
        $this->validationErrors = $errors;
        $this->setMessageTemplate('Limitations did not validate');
        parent::__construct($this->getBaseTranslation());
    }

    /**
     * Returns an array of limitation ValidationError objects.
     *
     * @return \Eki\NRW\Component\Core\FieldType\ValidationError[]
     */
    public function getLimitationErrors()
    {
        return $this->errors;
    }
}
