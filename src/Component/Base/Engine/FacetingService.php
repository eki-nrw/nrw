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

use Eki\NRW\Component\Faceting\Facet\FacetGroupInterface;
use Eki\NRW\Component\Faceting\Facet\FacetInterface;
use Eki\NRW\Component\Faceting\Facet\FacetValueInterface;

/**
 * Faceting Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface FacetingService
{
	public function createFacetGroup(); 
	public function loadFacetGroup($facetGroupId); 
	public function loadFacetGroupByIdentifier($facetGroupIdentifier); 
	public function deleteFacetGroup(FacetGroupInterface $facetGroup); 
	public function updateFacetGroup(FacetGroupInterface $facetGroup); 
	
	public function createFacet($facetIdentifier);	
	public function loadFacet($facetId);
	public function loadFacetByIdentifier($facetIdentifier);
	public function deleteFacet(FacetInterface $facet);
	public function updateFacet(FacetInterface $facet);
	
	public function createFacetValue(FacetInterface $facet, $facetValueIdentifier);	
	public function loadFacetValue($facetValueId);
	public function loadFacetValueByIdentifier($facetIdentifier, $facetValueIdentifier);
	public function deleteFacetValue(FacetValueInterface $facetValue);
	public function updateFacetValue(FacetValueInterface $facetValue);

	public function getFacetValue($facetIdentifier, $facetValueIdentifier);
	
	public function getAssignedSubjects(array $facetValues, array $filters, $offset = 0, $limit = -1);
	public function getAssignedSubjectsCount(array $facetValues, array $filters);
	
	public function assignSubject($subject, FacetValueInterface $facetValue);
	public function unAssignSubject($subject, FacetValueInterface $facetValue);
	
	public function getSubjectFacetValues($subject);
	public function getSubjectFacetValuesCount($subject);
}
