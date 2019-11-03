<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Core\Engine;

use Eki\NRW\Component\Base\Engine\Engine;
use Eki\NRW\Component\Base\Engine\LocatingService as LocatingServiceInterface;
use Eki\NRW\Component\Base\Engine\RelatingService;
use Eki\NRW\Component\SPBase\Persistence\Locating\Handler;

use Eki\NRW\Common\Res\Model\ResInterface;

use Eki\NRW\Component\Locating\Location\LocationInterface;
use Eki\NRW\Component\Locating\Location\Location;

use Eki\NRW\Component\Base\Exceptions\NotFoundException as BaseNotFoundException;
use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException as BaseInvalidArgumentException;
use Eki\NRW\Component\Base\Exceptions\UnauthorizedException as BaseUnauthorizedException;

use Eki\NRW\Component\Core\Base\Exceptions\NotFoundException;
use Eki\NRW\Component\Core\Base\Exceptions\UnauthorizedException;
use Eki\NRW\Component\Core\Base\Exceptions\InvalidArgumentException;

use Eki\NRW\Component\Locating\Composite\Constants;
use Eki\NRW\Component\Core\Engine\Locating\TypeMeaningHelper;

/**
 * Locating Service implementation.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
class LocatingService extends BaseService implements LocatingServiceInterface
{
	/**
	* @var Handler
	*/
	protected $locatingHandler;

	/**
	* @var TypeMeaningHelper
	*/
	private $typeMeaningHelper;
	
	/**
	* @var RelatingService
	*/
	private $relatingService;
	
	public function __construct(
		Engine $engine,
		array $settings,
		Handler $handler,
	)
	{
		$this->locatingHandler = $handler;
		$this->typeMeaningHelper = new Locating\TypeMeaningHelper();
		$this->relatingService = $this->engine->getRelatingService();
		
		parent::__construct($engine, $settings);
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function createLocation($identifier, $type = null)
	{
        if (!$this->permissionResolver->canUser(
        	'location', 
        	'create', 
        	array(
        		'identifier' => $identifier,
        		'type' => $type
        	)
        )) 
        {
            throw new UnauthorizedException(
                'location',
                'create',
                array(
                	'identifier' => $identifier,
        			'type' => $type
                )
            );
        }
        
		$this->beginTransaction();
        try 
        {
			$location = $this->locatingHandler->create($identifier);
			if ($type !== null)
				$location->setLocationType($type);
			
			$this->commit();
		}
		catch(\Exception $e)
		{
			$this->rollback();
			throw $e;
		}
		
		return $location;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function loadLocation($id)
	{
        if (!$this->permissionResolver->canUser('location', 'read', 
        	array(
        		'id' => $id
        	)
        )) 
        {
            throw new UnauthorizedException(
                'location',
                'read',
                array(
        			'id' => $id
                )
            );
        }
		
		try
		{
			$location = $this->locatingHandler->load($id);
		} 
		catch (BaseNotFoundException $e) 
		{
            throw new NotFoundException(
                'Location',
                array(
                    'id' => $id
                ),
                $e
            );
		}
		
		return $location;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function updateLocation(LocationInterface $location)
	{
        if (!$this->permissionResolver->canUser('location', 'edit', $location)) 
        {
            throw new UnauthorizedException(
                'location',
                'edit',
                array(
                    'id' => $location->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->locatingHandler->update($location);
			
			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
		
		return $location;
	}
	
	/**
	* @inheritdoc
	* 
	*/
	public function deleteLocation(LocationInterface $location)
	{
        if (!$this->permissionResolver->canUser('location', 'remove', $location)) 
        {
            throw new UnauthorizedException(
                'Location',
                'remove',
                array(
                    'id' => $location->getId()
                )
            );
        }

		$this->beginTransaction();
		try
		{ 
			$this->locatingHandler->delete($location);
			
			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollback();
			throw $e;			
		}
	}

	/**
	* Create a location group with the location as center
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Location as LocationGroup
	*/
	public function createLocationGroup()
	{
        if (!$this->permissionResolver->canUser('locating', 'create', 'location')) 
        {
            throw new UnauthorizedException(
                'Locating',
                'create',
                array(
                	'locating_type' => 'location'
                )
            );
        }
        
        try
        {
			$locationGroup = $this->relatingService->createRelation(
				'group', 
			    $this->typeMeaningHelper->locatingIsLocation->getType()
			);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		return $locationGroup;
	}
	
	/**
	* Join the given location to the location group
	* 
	* @param LocationGroup $group
	* @param LocationInterface $location
	* 
	* @return void
	*/
	public function joinLocation(LocationGroup $group, LocationInterface $location)
	{
        if (!$this->permissionResolver->canUser('locating', 'edit', $group)) 
        {
            throw new UnauthorizedException(
                'Locating',
                'edit',
                array(
                	'id' => $group->getId()
                )
            );
        }

        $group = $this->relatingService->loadRelation($group->getId());
        try
        {
        	$group->join($location);
        	$this->relatingService->updateRelation($group);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		return $group;
	}
	
	/**
	* Leave the given locationfrom the location group
	* 
	* @param LocationGroup $group
	* @param LocationInterface $location
	* 
	* @return void
	*/
	public function leaveLocation(LocationGroup $group, LocationInterface $location)
	{
        if (!$this->permissionResolver->canUser('locating', 'edit', $group)) 
        {
            throw new UnauthorizedException(
                'Locating',
                'edit',
                array(
                	'id' => $group->getId()
                )
            );
        }

        $group = $this->relatingService->loadRelation($group->getId());
        try
        {
        	$group->leave($location);
        	$this->relatingService->updateRelation($group);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		return $group;
	}

	/**
	* Create a location group as aggregation with the associated location
	* 
	* @param LocationInterface $location
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Aggregation
	*/
	public function createAggregation(LocationInterface $location)
	{
        if (!$this->permissionResolver->canUser('locating', 'create', 'aggregation')) 
        {
            throw new UnauthorizedException(
                'Locating',
                'create',
                array(
                	'locating_type' => 'aggregation'
                )
            );
        }
        
        $location = $this->loadLocation($location->getId());
        try
        {
			$aggregation = $this->relatingService->createRelation(
				'group', 
			    $this->typeMeaningHelper->locatingIsAggregation->getType()
			);
			
			$aggregation->atLocation($location);
			$this->relatingService->updateRelation($aggregation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		return $aggregation;
	}
	
	public function aggregate(Aggregation $aggregation, ResInterface $res)
	{
        if (!$this->permissionResolver->canUser('locating', 'edit', $aggregation)) 
        {
            throw new UnauthorizedException(
                'Locating',
                'edit',
                array(
                	'id' => $aggregation->getId()
                )
            );
        }

        $aggregation = $this->relatingService->loadRelation($aggregation->getId());
        try
        {
        	$aggregation->aggregate($res);
        	$this->relatingService->updateRelation($aggregation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		return $aggregation;
	}
	
	public function segregate(Aggregation $aggregation, ResInterface $res)
	{
        if (!$this->permissionResolver->canUser('locating', 'edit', $aggregation)) 
        {
            throw new UnauthorizedException(
                'Locating',
                'edit',
                array(
                	'id' => $aggregation->getId()
                )
            );
        }

        $aggregation = $this->relatingService->loadRelation($aggregation->getId());
        try
        {
        	$aggregation->segregate($res);
        	$this->relatingService->updateRelation($aggregation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		return $aggregation;
	}
	
	/**
	* Create a location group as composition with the composed location
	* 
	* @param LocationInterface $location
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Composition
	*/
	public function createComposition(LocationInterface $location)
	{
        if (!$this->permissionResolver->canUser('locating', 'create', 'composition')) 
        {
            throw new UnauthorizedException(
                'Locating',
                'create',
                array(
                	'locating_type' => 'composition'
                )
            );
        }
        
        $location = $this->loadLocation($location->getId());
        try
        {
			$composition = $this->relatingService->createRelation(
				'group', 
			    $this->typeMeaningHelper->locatingIsComposition->getType()
			);
			
			$composition->atLocation($location);
			$this->relatingService->updateRelation($composition);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		return $composition;
	}

	public function compose(Composition $composition, ResInterface $res)
	{
        if (!$this->permissionResolver->canUser('locating', 'edit', $composition)) 
        {
            throw new UnauthorizedException(
                'Locating',
                'edit',
                array(
                	'id' => $composition->getId()
                )
            );
        }

        $composition = $this->relatingService->loadRelation($composition->getId());
        try
        {
        	$composition->compose($res);
        	$this->relatingService->updateRelation($composition);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		return $composition;
	}
	
	public function decompose(Composition $composition, ResInterface $res)
	{
        if (!$this->permissionResolver->canUser('locating', 'edit', $composition)) 
        {
            throw new UnauthorizedException(
                'Locating',
                'edit',
                array(
                	'id' => $composition->getId()
                )
            );
        }

        $composition = $this->relatingService->loadRelation($composition->getId());
        try
        {
        	$composition->decompose($res);
        	$this->relatingService->updateRelation($composition);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		return $composition;
	}
}
