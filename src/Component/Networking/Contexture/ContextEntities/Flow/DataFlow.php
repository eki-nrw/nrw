<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Networking\Contexture\ContextEntities\Flow;

use Eki\NRW\Component\Contexture\ContextEntities\Flow\DataFlow as BaseDataFlow;
use Eki\NRW\Component\Networking\Agent\AgentInterface;
use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;

/**
* Data Flow in Networking:
* + Data is Resource
* + Entity is Agent
* 
* @author Nguyen Tien Hy <ngtienhy@gmail.com>s
*/
class DataFlow extends BaseDataFlow
{
	/**
	* Constructor
	* 
	* @pram string $name
	* @param EventDispatcher $dispatcher
	* 
	*/
	public function __construct($name, EventDispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
		
		parent::__construct(
			$name,
			$dispatcher,
			function ($data) {
				return $data instanceof ResourceInterface;
			},
			function ($fromEntity, $toEntity) {
				return
					$fromEntity instanceof AgentInterface
					and 
					$toEntity instanceof AgentInterface
				;	
			}
		);		
	}
}
