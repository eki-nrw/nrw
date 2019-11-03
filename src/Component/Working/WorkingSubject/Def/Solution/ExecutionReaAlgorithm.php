<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Solution;

use Eki\NRW\Component\Processing\Solution\Solution\ByStep\Algorithm;
use Eki\NRW\Component\Processing\Solution\Context\ContextInterface;

use Eki\NRW\Mdl\Processing\Element;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ExecutionReaAlgorightm extends Algorithm
{
	public function __construct()
	{
		parent::__construct(
			array(),
			array($this->getStep0(), $this->getStep1(), $this->getStep2(), $this->getStep3())
		);
	}
	
	private function getStep0()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$situation = $context->get('situation');
				
				$contexts = $situation['contexts'];
				$processingTool = $contexts['processingTool'];
				$process = $processingTool->createFrame("processing.process.execution.rea");
				
				$solving = $context->get('solving');
				$solving['solver'] = $process;
				$context->set('solving', $solving);
				
				return $context;
			},
			"create_process"
		);
		
		return $step;
	}

	private function getStep1()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$situation = $context->get('situation');

				$solving = $context->get('solving');
				$process = $solving['solver'];
				
				$situation = $context->get('situation');
				$execution = $situation['acting'];
				$executionKey = $situation['actingKey'];
				$process->in(new Element($executionKey, $executionKey), $situation['contexts']);
				
				return $context;
			},
			"in_process"
		);
		
		return $step;
	}

	private function getStep2()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$situation = $context->get('situation');

				$solving = $context->get('solving');
				$process = $solving['solver'];
				
				$situation = $context->get('situation');
				$outputExecution = $situation['acting'];
				$process->actuate(isset($situation['contexts']) ? $situation['contexts'] : []);
				
				return $context;
			},
			"do_process"
		);
		
		return $step;
	}

	private function getStep3()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$solving = $context->get('solving');
				$process = $solving['solver'];
				
				$situation = $context->get('situation');
				$execution = $situation['acting'];
				$actingKey = $situation['actingKey'];
				$frameOutput = new Element($actingKey, $execution);
				$frameOutput = $process->out(
					$frameOutput, 
					isset($situation['contexts']) ? $situation['contexts'] : []
				);
				
				return $context;
			},
			"process_out"
		);
		
		return $step;
	}
	
}
