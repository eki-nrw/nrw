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

use Eki\NRW\Mdl\Processing\Solution\Solution\ByStep\Algorithm;
use Eki\NRW\Mdl\Processing\Solution\Context\ContextInterface;

use Eki\NRW\Mdl\Processing\Element;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class PlanExecuteAlgorightm extends Algorithm
{
	public function __construct()
	{
		parent::__construct(
			array(),
			array(
				$this->getStepCreateProcess(), 
				$this->getStepCreateProcessIfAny(), 
				$this->getStepInProcess(), 
				$this->getStepDoProcess(),
				$this->getStepProcessOut(), 
			)
		);
	}
	
	private function getStepCreateProcess()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$situation = $context->get('situation');
				
				$contexts = $situation['contexts'];
				$processingTool = $contexts['processingTool'];
				$process = $processingTool->createFrame("processing.process.plan.execute");
				
				$solving = $context->get('solving');
				$solving['solver'] = $process;
				$context->set('solving', $solving);
				
				return $context;
			},
			"create_process"
		);
		
		return $step;
	}
	
	private function getStepCreateProcessIfAny()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$solving = $context->get('solving');
				if (isset($solving['solver']) or $solving['solver'] === null)
					return $context;

				$situation = $context->get('situation');
				$contexts = $situation['contexts'];
				$processingTool = $contexts['processingTool'];
				$process = $processingTool->createFrame("processing.process.plan.execute");
				
				$solving['solver'] = $process;
				$context->set('solving', $solving);
				
				return $context;
			},
			"create_process_if_any"
		);
		
		return $step;
	}

	private function getStepInProcess()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$situation = $context->get('situation');

				$solving = $context->get('solving');
				$process = $solving['solver'];
				
				$situation = $context->get('situation');
				$activity = $situation['acting'];
				$actingKey = $situation['actingKey'];
				$process->in(new Element($actingKey, $activity), $situation['contexts']);
				
				return $context;
			},
			"in_process"
		);
		
		return $step;
	}

	private function getStepInProcessAny()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$situation = $context->get('situation');
				$contexts = $situation['contexts'];

				$solving = $context->get('solving');
				if (isset($solving['solver']) or $solving['solver'] === null)
				{
					$processingTool = $contexts['processingTool'];
					$process = $processingTool->createFrame("processing.process.plan.execute");
					
					$solving['solver'] = $process;
					$context->set('solving', $solving);
					$solving = $context->get('solving');
				}

				$process = $solving['solver'];
				$activity = $situation['acting'];
				$actingKey = $situation['actingKey'];
				$process->in(new Element($actingKey, $activity), $situation['contexts']);
				
				return $context;
			},
			"in_process_any"
		);
		
		return $step;
	}

	private function getStepDoProcess()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$situation = $context->get('situation');

				$solving = $context->get('solving');
				$process = $solving['solver'];
				
				$situation = $context->get('situation');
				$process->actuate(isset($situation['contexts']) ? $situation['contexts'] : []);
				
				return $context;
			},
			"do_process"
		);
		
		return $step;
	}

	private function getStepProcessOut()
	{
		$step = new Step(
			function (ContextInterface $context) {
				$solving = $context->get('solving');
				$process = $solving['solver'];
				
				$situation = $context->get('situation');
				$activity = $situation['acting'];

				$frameOutput = $process->out(
					new Element(null, $activity), 
					isset($situation['contexts']) ? $situation['contexts'] : []
				);
				
				return $context;
			},
			"process_out"
		);
		
		return $step;
	}

}
