<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\MVC\Symfony\Security\Authorization\Voter;

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\MVC\Symfony\Security\Authorization\Attribute as AuthorizationAttribute;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Voter to test access to a ValueObject from Engine (e.g. Content, Location...).
 */
class ValueObjectVoter implements VoterInterface
{
    /**
     * @var \Eki\NRW\Component\Base\Engine\Engine
     */
    private $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function supportsAttribute($attribute)
    {
        return $attribute instanceof AuthorizationAttribute && isset($attribute->limitations['subjectObject']);
    }

    public function supportsClass($class)
    {
        return true;
    }

    /**
     * Returns the vote for the given parameters.
     * Checks if user has access to a given action on a given value object.
     *
     * $attributes->limitations is a hash that contains:
     *  - 'valueObject' - The ValueObject to check access on (Eki\NRW\Component\Base\Values\ValueObject). e.g. Location or Content.
     *  - 'targets' - The location, parent or "assignment" value object, or an array of the same.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @see \Eki\NRW\Component\Base\Engine\PermissionResolver::canUser()
     *
     * @param TokenInterface $token      A TokenInterface instance
     * @param object         $object     The object to secure
     * @param array          $attributes An array of attributes associated with the method being invoked
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        foreach ($attributes as $attribute) {
            if ($this->supportsAttribute($attribute)) {
                $targets = isset($attribute->limitations['targets']) ? $attribute->limitations['targets'] : null;
                if (
                    $this->engine->getPermissionResolver()->canUser(
                        $attribute->service,
                        $attribute->function,
                        $attribute->limitations['subjectObject'],
                        $targets
                    ) === false
                ) {
                    return VoterInterface::ACCESS_DENIED;
                }

                return VoterInterface::ACCESS_GRANTED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
