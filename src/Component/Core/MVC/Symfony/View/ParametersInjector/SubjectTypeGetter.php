<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\MVC\Symfony\View\ParametersInjector;

use Eki\NRW\Component\Core\MVC\Symfony\View\Event\FilterViewParametersEvent;
use Eki\NRW\Component\Core\MVC\Symfony\View\SubjectView;
use Eki\NRW\Component\Core\MVC\Symfony\View\ViewEvents;
use Eki\NRW\Mdl\Working\SubjectTypeGetterInterface;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Injects the 'viewBaseLayout' view parameter, set by the container parameter.
 */
class SubjecTypeGetter implements EventSubscriberInterface
{
	/**
	* @var SubjectTypeGetterInterface
	*/
	protected $subjectTypeGetter;

    public function __construct(SubjectTypeGetterInterface $subjectTypeGetter)
    {
        $this->subjectTypeGetter = $subjectTypeGetter;
    }

    public static function getSubscribedEvents()
    {
        return [ViewEvents::FILTER_VIEW_PARAMETERS => 'injectSubjectTypeGetter'];
    }

    public function injectSubjectTypeGetter(FilterViewParametersEvent $event)
    {
    	if (!$event->getView() instanceof SubjectView)
    		return;
    		
        $event->getParameterBag()->set('subjectTypeGetter', $this->subjectTypeGetter);
    }
}
