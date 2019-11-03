<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 
namespace Eki\NRW\Component\Base\Engine\Permission\Role\Limitation;

use Eki\NRW\Component\Base\Engine\Permission\Role\Limitation;

/*
 * A always blocking limitation
 *
 * Meant mainly for use with not implemented limitations, like legacy limitations which are not used by Platform stack.
 */
class BlockingLimitation extends Limitation
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * Create new Blocking Limitation with identifier injected dynamically.
     *
     * @throws \InvalidArgumentException If $identifier is empty
     *
     * @param string $identifier The identifier of the limitation
     * @param array $limitationValues
     */
    public function __construct($identifier, array $limitationValues)
    {
        if (empty($identifier)) {
            throw new \InvalidArgumentException('Argument $identifier can not be empty');
        }

        parent::__construct(array('identifier' => $identifier, 'limitationValues' => $limitationValues));
    }

    /**
     * @see \Eki\NRW\Component\Base\Engine\Permission\Role\Limitation::getIdentifier()
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
