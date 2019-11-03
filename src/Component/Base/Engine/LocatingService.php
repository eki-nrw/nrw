<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine;

use Eki\NRW\Common\Res\Model\ResInterface;
use Eki\NRW\Component\Locating\Location\LocationInterface;
use Eki\NRW\Component\Locating\Composite\Location as LocationGroup;
use Eki\NRW\Component\Locating\Composite\Aggregation;
use Eki\NRW\Component\Locating\Composite\Composition;

use Eki\NRW\Component\Base\Exceptions\InvalidArgumentException;

/**
 * Locating Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface LocatingService
{
	/**
	* Create new location
	* 
	* @param string $identifier
	* @pẩm string |null $type
	* 
	* @return \Eki\NRW\Component\Locating\Location\LocationInterface
	* 
	* @throws
	*/
	public function createLocation($identifier, $type = null);
	
	/**
	* Load the given location
	* 
	* @param mixed $id
	* 
	* @return \Eki\NRW\Component\Locating\Location\LocationInterface
	* 
	* @throws
	* 
	*/
	public function loadLocation($id);
	
	/**
	* Update the given location
	* 
	* @param \Eki\NRW\Component\Locating\Location\LocationInterface $location
	* 
	* @return void
	* 
	* @throws
	*/
	public function updateLocation(LocationInterface $location);
	
	/**
	* Delete the given location
	* 
	* @param \Eki\NRW\Component\Locating\Location\LocationInterface $location
	* 
	* @return void
	*/
	public function deleteLocation(LocationInterface $location);

	/**
	* Create a location group with the location as center
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Location as LocationGroup
	*/
	public function createLocationGroup();
	
	/**
	* Join the given location to the location group
	* 
	* @param LocationGroup $group
	* @param LocationInterface $location
	* 
	* @return void
	*/
	public function joinLocation(LocationGroup $group, LocationInterface $location);
	
	/**
	* Leave the given locationfrom the location group
	* 
	* @param LocationGroup $group
	* @param LocationInterface $location
	* 
	* @return void
	*/
	public function leaveLocation(LocationGroup $group, LocationInterface $location);

	/**
	* Create a location group as aggregation with the associated location
	* 
	* @param LocationInterface $location
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Aggregation
	*/
	public function createAggregation(LocationInterface $location);
	
	/**
	* Aggregate an object $res to the location aggregation
	* 
	* @param \Eki\NRW\Component\Locating\Composite\Aggregation $aggregation
	* @param \Eki\NRW\Common\Res\Model\ResInterface $res
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Aggregation
	*/
	public function aggregate(Aggregation $aggregation, ResInterface $res);

	/**
	* Segregate an object $res from the location aggregation
	* 
	* @param \Eki\NRW\Component\Locating\Composite\Aggregation $aggregation
	* @param \Eki\NRW\Common\Res\Model\ResInterface $res
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Aggregation
	*/
	public function segregate(Aggregation $aggregation, ResInterface $res);
	
	/**
	* Create a location group as composition with the composed location
	* 
	* @param LocationInterface $location
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Composition
	*/
	public function createComposition(LocationInterface $location);

	/**
	* Compose an object $res to the location composition
	* 
	* @param \Eki\NRW\Component\Locating\Composite\Composition $composition
	* @param \Eki\NRW\Common\Res\Model\ResInterface $res
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Composition
	*/
	public function compose(Composition $composition, ResInterface $res);
	
	/**
	* Decompose an object $res from the location composition
	* 
	* @param \Eki\NRW\Component\Locating\Composite\Composition $composition
	* @param \Eki\NRW\Common\Res\Model\ResInterface $res
	* 
	* @return \Eki\NRW\Component\Locating\Composite\Composition
	*/
	public function decompose(Composition $composition, ResInterface $res);
}
