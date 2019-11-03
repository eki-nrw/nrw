<?php
/**
 * This file is part of the Eki-NRW package.
 *
 * (c) Ekipower
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */ 

namespace Eki\NRW\Component\Base\Engine\Processing;

use Eki\NRW\Component\Procesing\FrameInterface;

/**
 * Process Service interface.
 */
interface FramingService
{
	public function createFrame($processingType);
	public function loadFrame($id);
	public function updateFrame(FrameInterface $frame);
	public function deleteFrame(FrameInterface $frame);
	public function loadOrCreateFrame($processingType, FrameInterface $frame);

	/**
	* Returns relational frame type
	* 
	* Relational frame type depends on:
	* + processing type
	* + the type (opposite of other type)
	* + option of processing type
	* 
	* @param string $frameType Type of the frame
	* @param string $processingType
	* @param string $continuation
	* @param array $contexts
	* 
	* @return string Type of relational frame
	*/
	public function getContinueFrameType($frameType, $processingType, $continuation, array $contexts = []);

	/**
	* Returns relational frame
	* 
	* @param FrameInterface $frame The frame
	* @param string $processingType
	* @param string $continuation
	* @param bool $back True if getting object. False if getting other object interms of relationship
	* 
	* @return object The relational frame
	*/
	public function getContinuedFrame(FrameInterface $frame, $processingType, $continuation, $back = false);
	
	/**
	* Relating the frame with the relational frame by the relation of the given processing type and option
	* 
	* @param FrameInterface $frame
	* @param FrameInterface $otherFrame
	* @param string $processingType
	* @param string $continuation
	* 
	* @return \Eki\NRW\Component\Relating\Relation\RelationInterface
	*/
	public function continuingFrames(FrameInterface $frame, $relationFrame, $processingType, $continuation);
}
