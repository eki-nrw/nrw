<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\Subject\Importor\Activity\ExchangeActivity;

use Eki\NRW\Component\Working\Subject\Importor\BaseDelegateImportor;
use Eki\NRW\Mdl\Working\Subject\DirectorInterface;

use ReflectionClass;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class DelegateImportor extends BaseDelegateImportor
{
	public function __construct(DirectorInterface $director)
	{
		parent::__construct($director, new ReflectionClass($this));
	}
}
