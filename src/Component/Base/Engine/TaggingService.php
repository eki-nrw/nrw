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

use Eki\NRW\Component\Base\Tagging\TagInterface;

/**
 * Tagging Service interface.
 * 
 * @author Nguyen Tien Hy <ngtienhy@gmail.com>
 * 
 */
interface TaggingService
{
	public function loadTag($tagId);
	
	public function loadTagByUrl($url);
	
	public function loadTagChildren(TagInterface $tag = null, $offset = 0, $limit = -1);
	
	public function getTagChildrenCount(TagInterface $tag = null);

	public function loadTagsByKeyword($keyword, $offset = 0, $limit = -1);
	
	public function searchTags($searchString, $offset = 0, $limit = -1);
	
	public function loadTagSynonyms(TagInterface $tag, $offset = 0, $limit = -1);

	public function getTagSynonymsCount(TagInterface $tag);
	
	public function getRelatedSubjects(TagInterface $tag, $subjectInfo = null, $offset = 0, $limit = -1);
	
	public function getRelatedSubjectsCount(TagInterface $tag);
	
	public function createTag();

	public function updateTag(TagInterface $tag);

	public function addSynonym(Tag $mainTag);
	
	public function convertToSynonym(TagInterface $tag, TagInterface $mainTag);
	
	public function mergeTags(TagInterface $tag, TagInterface $targetTag);
	
	public function copySubtree(TagInterface $tag, TagInterface $targetParentTag = null);

	public function moveSubtree(TagInterface $tag, TagInterface $targetParentTag = null);
	
	public function deleteTag(TagInterface $tag);
}
