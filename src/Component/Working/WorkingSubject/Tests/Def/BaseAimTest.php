<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Tests\Def;

use Eki\NRW\Component\Working\WorkingSubject\Def\AbstractAim;

use Eki\NRW\Component\Working\WorkingSubject\Tests\Helper\WorkingTool;
use Eki\NRW\Component\Working\WorkingSubject\Tests\Helper\Logger;

use PHPUnit\Framework\TestCase;
use stdClass;
use ReflectionClass;

abstract class BaseAimTest extends TestCase
{
	const AIM_CLASS = AbstractAim::class;
	const BASE_CLASS = "Eki\\NRW\\Component\\Working\\WorkingSubject\\Def\\AbstractAim";
	
	public function testConstructor()
	{
		$aim = $this->createAim([]);
	}

	public function testSupport()
	{
		$aim = $this->createAim([]);
		
		$subject = new stdClass;
		$subject->type = $this->__getAimSupportedSubjectType();
		self::assertTrue($aim->support($this->__getAimName(), $subject));
	}

	public function testAim()
	{
		$aim = $this->createAim([]);

		$subject = new stdClass;
		$subject->type = $this->__getAimSupportedSubjectType();
		$aim->aim($this->__getAimName(), $subject);
	}

	protected function __getAimName()
	{
		return (new ReflectionClass(static::AIM_CLASS))->getShortName();
	}

	protected function __getAimSupportedSubjectType()
	{
		$rf = new ReflectionClass(static::AIM_CLASS);
		$rfRoot = new ReflectionClass(static::BASE_CLASS);

		$nsp = $rf->getNamespaceName();
		$rootNsp = $rfRoot->getNamespaceName();
		
		$type = substr($nsp, strlen($rootNsp) + 1);
		$type = str_replace("\\", ".", strtolower($type));

		return $type;		
	}

	protected function __createAim($aimClass, array $options)
	{
		$r = new ReflectionClass($aimClass);
		if ($r->isAbstract())
		{
			$aim = $this->getMockBuilder($aimClass)
				->setMethods(['_getReflectionClassOfCurrentObject'])
				->setConstructorArgs([$options])
				->getMockForAbstractClass()
			;
			
			$class = static::AIM_CLASS;
			
			$aim
				->expects($this->any())
				->method('_getReflectionClassOfCurrentObject')
				->will($this->returnCallback(function () use ($class) {
					echo "++++++++++++++++++++++\n";
					return new ReflectionClass($class);
				}))
			;
		}
		else
			$aim = new $aimClass($options);
		
		$aim->setTool(WorkingTool::createTool(
			$this, array(
				stdClass::class => function ($subject) {
					if (isset($subject->type))
						return $subject->type;
					return false;
				},
			)
		));
		
		$aim->setLogger(Logger::createConsoleLogger($this));
		
		return $aim;
	}
	
	protected function createAim(array $options)
	{
		return $this->__createAim(static::AIM_CLASS, $options);	
	}
}
