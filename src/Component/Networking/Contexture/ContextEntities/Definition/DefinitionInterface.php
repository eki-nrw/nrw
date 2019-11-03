<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Contexture\ContextEntities;

use Eki\NRW\Component\Contexture\ContextEntities\Definition\DefinitionInterface as BaseDefinitionInterface;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
interface DefinitionInterface extends BaseDefinitionInterface
{
	const SCOPE_INTERNAL = "internal";
	const SCOPE_EXTERNAL = "external";
	
	const FLOW_INCOMING = "incoming";
	const FLOW_OUTGOING = "outgoing";
	const FLOW_WORKING = "working";
}
