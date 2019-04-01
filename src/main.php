<?php

namespace TemplateEngine;

use cli;

require_once __DIR__.'/../vendor/autoload.php';

cli\Colors::enable();

if (!getenv('TEMPLATEENGINE_TEMPLATEFILE')) {
	cli\line("");
	cli\err("%YYou must pass in a template source file!");
	cli\line("See Readme.md for usage examples.%n");
	cli\line("");
	exit(1);
}

$templateFile       = '/config/'.getenv('TEMPLATEENGINE_TEMPLATEFILE');
$templateOutputFile = '/config/'.getenv('TEMPLATEENGINE_OUTPUTFILE');
$globals            = '/config/'.getenv('TEMPLATEENGINE_PARAMS');
$mixins             = '/config/'.getenv('TEMPLATEENGINE_MIXIN');

if (!is_file($templateFile)) {
	cli\line("");
	cli\err("%YTemplate source file $templateFile does not exist!%n");
	cli\line("");
	exit(1);
}

$config = new Config($globals);

if ($mixins) {
	$config->addMixinsFile($mixins);
}

$template = new Template($config, $templateFile);
$template->writeRenderedOutput($templateOutputFile);

cli\line("");