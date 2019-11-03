<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\Activity\Process\Processing;

use Eki\NRW\Mdl\Processing\Actuate\Actuator as BaseActuator;
use Eki\NRW\Common\Compose\ObjectItem\ObjectItem;
use Eki\NRW\Component\Resourcing\Resource\ResourceInterface;
use Eki\NRW\Component\Resourcing\Resource\Provider\RegistryInterface as ResourceProviderRegistryInterface;
use Eki\NRW\Component\Resourcing\Resource\Provider\ProviderInterface as ResourceProviderInterface;

use Eki\NRW\Component\Working\Activity\InputProcessActivity;
use Eki\NRW\Component\Working\Activity\OutputProcessActivity;

//use Closure;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class Actuator extends BaseActuator
{
	//const TYPE = "processing.frame.process.actuator.working_subject.def.activity.process";

	public function __construct()
	{
		parent::__construct(
			$this->materialUnpacker,
			$this->producer(),
			$this->productPacker(),
			$this->inputSubjectChecker(),
			$this->materialChecker(),
			$this->outputSubjectChecker()
		);
	}
	
	protected function materialUnpacker()
	{
		return
			function ($subject, array $contexts) {
				return $subject->getObjectItem();
			}
		;
	}
	
	protected function inputSubjectChecker()
	{
		return
			function ($subject, array $contexts) {
				return $subject instanceof InputProcessActivity;
			}
		;
	}

	protected function productPacker()
	{
		return
			function ($product, $subject, array $contexts) {
				$subject->setObjectItem($product);
				return $subject;
			}
		;
	}

	protected function outputSubjectChecker()
	{
		return
			function ($subject, array $contexts) {
				return $subject instanceof OutputProcessActivity;
			}
		;
	}
	
	protected function producer()
	{
		return
			function (array $materials, array $contexts) {
				$resourcingTool = $contexts['resourcingTool'];

				if (null === ($resourceProvider = $resourcingTool->getResourceProviderRegistry()->getProvider($materials)))
					return null;
				
				if (null === ($objectItem = $resourceProvider->get($materials, $contexts)))
					return null;
				
				return $objectItem;
			}
		;
	}
	
	protected function materialChecker()
	{
		return
			function ($material) {
				if (!$material instanceof ObjectItem)
					return false;
				if (!$material->getItem() instanceof ResourceInterface)
					return false;
					
				return true;
			}
		;
	}
}
