<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Mdl\REA\Tests\Processing\Process;

use Eki\NRW\Mdl\REA\Processing\Process\HasProcessTrait;
use Eki\NRW\Mdl\REA\Processing\Process\ProcessInterface;

use PHPUnit\Framework\TestCase;

class HasProcessTraitTest extends TestCase
{
	public function testHasProcess()
	{
		$trait = $this->getMockBuilder(HasProcessTrait::class)->getMockForTrait();
		$process = $this->getMockBuilder(ProcessInterface::class)->getMock();
		
		$trait->setProcess($process);
		$this->assertEquals($process, $trait->getProcess());
	}
}
