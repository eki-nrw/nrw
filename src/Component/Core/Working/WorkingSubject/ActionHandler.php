<?php
/**
 * This file is part of the Exchanging\ExchangeItem.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Working\WorkingSubject;

use Eki\NRW\Component\Working\WorkingSubject\AbstractActionHandler;
use Eki\NRW\Component\Core\MVC\Symfony\ViewEvents;

use Eki\NRW\Component\Core\MVC\Symfony\View\Action\Executor;
use Eki\NRW\Component\Core\MVC\Symfony\View\Builder\ViewBuilderRegistry;
use Eki\NRW\Component\Core\MVC\Symfony\View\Event\FilterViewBuilderParametersEvent;
use Eki\NRW\Component\Core\MVC\Symfony\View\ViewEvents;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
* @author Nguyen Tien Hy <ngtienhy@gmail.com>
*/
class ActionHandler extends AbstractActionHandler
{
	/**
	* @var ViewBuilderRegistry
	*/
	protected $viewBuilderRegistry;
	
	/**
	* @var Executor
	*/
	protected $executor;
	
	/**
	* @var EventDispatcherInterface
	*/
	protected $eventDispatcher;
	
	public function __construct(
		array $classnames, 
		ViewBuilderRegistry $viewBuilderRegistry,
		Executor $executor,
		EventDispatcherInterface $eventDispatcher
	)
	{
		parent::__construct($classnames);
		
		$this->viewBuilderRegistry = $viewBuilderRegistry;
		$this->executor = $executor;
		$this->eventDispatcher = $eventDispatcher;
	}

	/**
	* @internal
	* 
	* @param string $actionName
	* 
	* @return bool
	*/
	protected function supportAction($actionName, $subject)
	{
		return true;
	}

	/**
	* @inheritdoc
	* 
	*/
	public function handle($subject, $actionName, array $contexts = [])
	{
		if (!$this->support($subject, $actionName))
			throw new \DomainException(sprintf(
				"Subject or action not support. Given subject class %s. Given action '%s'",
				get_class($subject), $actionName
			));

		$request = new Request();
		$request->atributes->set('_handler', 'eki_working:doAction');   // ??????
		$request->atributes->set('viewType', 'working_action');         // ??????
		$request->atributes->set('subject', $subject);
		$request->atributes->set('action', array(
			'type' => 'workflow',
			'workflow_name' => $this->getWorkingType(),
			'name' => $actionName,
		));
		$request->atributes->set('workingType', $this->getWorkingType());
		foreach($contexts as $keyContext => $context)
		{
			$request->attributes->set($keyContext, $context);
		}

        if (null === ($viewBuilder = $this->viewBuilderRegistry->getFromRegistry($request->attributes->get('_handler')))) 
            return;

        $parameterEvent = new FilterViewBuilderParametersEvent(clone $request);
        $this->eventDispatcher->dispatch(ViewEvents::FILTER_BUILDER_PARAMETERS, $parameterEvent);
        $view = $viewBuilder->buildView($parameterEvent->getParameters()->all());
        //$request->attribute->add("view", $view);
        
        $this->executor->execute($view);
	}
}
