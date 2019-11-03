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

use Eki\NRW\Component\Base\Exceptions\UnauthorizedException as BaseUnauthorizedException;
use Eki\NRW\Component\Core\Base\Translatable;
use Eki\NRW\Component\Core\Base\TranslatableBase;

use Exception;

/**
 * UnauthorizedException Exception implementation.
 *
 * Use:
 *   throw new UnauthorizedException( 'agent', 'read', array( 'agentId' => 42 ) );
 */
class UnauthorizedException extends BaseUnauthorizedException implements Httpable, Translatable
{
    use TranslatableBase;

    /**
     * Generates: User does not have access to '{$function}' '{$service}'[ with: %property.key% '%property.value%'].
     *
     * Example: User does not have access to 'read' 'content' with: id '44', type 'article'
     *
     * @param string $service The service name should be in sync with the name of the domain object in question
     * @param string $function
     * @param array $properties Key value pair with non sensitive data on what kind of data user does not have access to
     * @param \Exception|null $previous
     */
    public function __construct($service, $function, array $properties = null, Exception $previous = null)
    {
        $this->setMessageTemplate("User does not have access to '%function%' '%service%'");
        $this->setParameters(['%service%' => $service, '%function%' => $function]);

        if ($properties) {
            $this->setMessageTemplate("User does not have access to '%function%' '%service%' with: %with%'");
            $with = [];
            foreach ($properties as $name => $value) {
                $with[] = "{$name} '{$value}'";
            }
            $this->addParameter('%with%', implode(', ', $with));
        }

        parent::__construct($this->getBaseTranslation(), self::UNAUTHORIZED, $previous);
    }
}
