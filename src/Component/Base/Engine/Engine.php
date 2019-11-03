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

/**
 * Engine interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface Engine extends Transacting
{
	/**
	* Returns the name of engine
	* 
	* @return string
	*/
	public function getEngineName();
	
	/**
	* Get a service by name
	* 
	* @param string $serviceName
	* 
	* @return object
	*/
	public function getService($serviceName);

	/**
	* Get Role Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\RoleService
	*/
	public function getRoleService();

	/**
	* Get Networking Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\NetworkingService
	*/
	public function getNetworkingService();

	/**
	* Returns Resourcing Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\ResourcingService;
	*/	
	public function getResourcingService();

	/**
	* Get Processing Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\ProessingService
	*/	
	public function getProcessingService();
	
	/**
	* Get Working Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\WorkingService
	*/
	public function getWorkingService();
	
	/**
	* Get Exchanging Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\ExchangingService
	*/
	public function getExchangingService();
	
	public function getClaimingService();
	
	/**
	* Get User Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\UserService
	*/
	public function getUserService();
	
	/**
	* Get Search Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\SeachService
	*/
	public function getSearchService();

	/**
	* Get Relating Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\RelatingService
	*/
	public function getRelatingService();

	/**
	* Get Locating Service
	* 
	* @return \Eki\NRW\Component\Base\Engine\LocatingService
	*/
	public function getLocatingService();
	
	// translation service
	
	// trash service
	
	// history service
	
	// vas service
	
	// accounting service
	
	// distribution service
	
	// app service
	
	// tagging service
	
	// faceting service
	
	// urlAlias service

    /**
     * Get Permission Resolver.
     *
     * @return \Eki\NRW\Component\Engine\Permission\PermissionResolver
     */
    public function getPermissionResolver();
}
