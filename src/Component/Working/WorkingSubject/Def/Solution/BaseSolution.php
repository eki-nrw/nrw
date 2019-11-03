<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Working\WorkingSubject\Def\Solution;

use Eki\NRW\Component\Processing\Solution\SolutionInterface;
use Eki\NRW\Mdl\Processing\Solution\Solution\ByStep\Solution;
use Eki\NRW\Mdl\Processing\Solution\Solution\ByStep\AlgorithmInterface;
use Eki\NRW\Mdl\Processing\Solution\Context\ContextInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
abstract class BaseSolution extends Solution implements SolutionInterface
{
	public function __construct(
		AlgorithmInterface $algorithm,
		Closure $problemChecker = null, Closure $situationChecker = null, Closure $solvingChecker = null
	)
	{
		$contextChecker = $this->getContextChecker(
			null === $problemChecker   ? $this->getDefaultProblemChecker()   : $problemChecker,
			null === $situationChecker ? $this->getDefaultSituationChecker() : $situationChecker,
			null === $solvingChecker   ? $this->getDefaultSolvingChecker()   : $solvingChecker
		);
		
		$stepGetter = function (ContextInterface $context) {
			$arr = $context->getContext();
			if (isset($arr['situation']['stepKey']))
				return $arr['situation']['stepKey'];
		};
		
		parent::__construct($algorithm, $contextChecker, $stepGetter);
	}

	protected function getContextChecker(Closure $problemChecker, Closure $situationChecker, Closure $solvingChecker)
	{
		$contextChecker = function ($context) use ($problemChecker, $situationChecker, $solvingChecker) {

			if ($context instanceof ContextInterface)
				$context = $context->getContext();

			$resolver = new OptionsResolver();
			
			$resolver->setAllowedTypes('problem', ['array']);
			$resolver->setAllowedTypes('situation', ['array']);
			$resolver->setAllowedTypes('solving', ['array']);
			
			if (true !== $problemChecker($context['problem']))
				return false;
			
			if (true !== $situationChecker($context['situation']))
				return false;

			if (true !== $solvingChecker($context['solving']))
				return false;
			
			return true;
		};
	}
	
	protected function getDefaultProblemChecker()
	{
		$checker = function ($context) {

			$resolver = new OptionsResolver();

			$resolver->setDefaults(array(
				//'format' => ObjectItem::class,
				//'of' => PlanItemInterface::class,
				//'element' => "objectItem",
			));
			
			try 
			{
				$resolver->resolve($context);
			}
			catch(\Exception $e)
			{
				return false;
			}
			
			return true;
		};
	}

	protected function getDefaultSituationChecker()
	{
		$checker = function ($context) {

			$resolver = new OptionsResolver();

			$resolver->setDefaults(array(
				'contexts' => array(),
			));

			$resolver->setAllowedTypes('acting', ['object']);
			$resolver->setAllowedTypes('actingKey', ['string']);
			$resolver->setAllowedTypes('contexts', ['array']);
			
			try 
			{
				$resolver->resolve($context);
			}
			catch(\Exception $e)
			{
				return false;
			}
			
			return true;
		};
	}

	protected function getDefaultSolvingChecker()
	{
		$checker = function ($context) {

			$resolver = new OptionsResolver();
			
			$resolver->setRequired('solver');

			$resolver->setDefaults(array(
				//'method' => array("create", "get"),
				//'format' => ObjectItem::class,
				'solver' => null,
			));
			
			$resolver->setRequired('solution');      // solution identifier
			$resolver->setAllowedTypes('solution', 'string');
			
			try 
			{
				$ctx = $resolver->resolve($context);
				
				if ($ctx['solution'] !== $this->getIdentifier())
					return false;
			}
			catch(\Exception $e)
			{
				return false;
			}
			
			return true;
	}
}
