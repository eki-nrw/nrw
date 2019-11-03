<?php

$metadata->isRoot = true;
$metadata->setIdGeneratorType(Doctrine\OXM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_AUTO);

$metadata->mapField(array(
	'id' => true,
	'fieldName' => 'id',
	'type' => 'integer'
));

//$metadata->setParentClasses(array());