<?php

//$metadata->setName("\\Eki\\NRW\\Component\\Networking\\Agent\\Agent");

$metadata->isRoot = true;
$metadata->setIdGeneratorType(Doctrine\OXM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_AUTO);

$metadata->mapField(array(
	'id' => true,
	'fieldName' => 'id',
	'type' => 'integer'
));

$metadata->mapField(array(
	'fieldName' => 'properties',
	'type' => 'array'
));

$metadata->mapField(array(
	'fieldName' => 'options',
	'type' => 'array'
));

$metadata->mapField(array(
	'fieldName' => 'attributes',
	'type' => 'array'
));

//$metadata->setParentClasses(array());