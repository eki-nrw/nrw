<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def;

use Eki\NRW\Component\Working\WorkingSubject\Def\AbstractAim;
use Eki\NRW\Component\Working\WorkingSubject\Def\Definitions;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class TransitionImport extends AbstractAim
{
	/**
	* @inheritdoc
	* 
	*/	
	protected function _supportType($subject)
	{
		return true;  // any type
	}
	
	/**
	* @inheritdoc
	* 
	*/	
	protected function onAim($name, $subject, array $contexts)
	{
		if (!isset($contexts['importSource']))
		{
			return;
		}
		
		$subject = $this->getTool()->getWorkingSubject(Definitions::WORKING_TYPE, $subject)
			->define($contexts['importSource'], $contexts)
			->getSubject()
		;
	}
}
