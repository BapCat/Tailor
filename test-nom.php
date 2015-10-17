#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use BapCat\Persist\Drivers\Filesystem\FilesystemDriver;
use BapCat\Tailor\Tailor;
use BapCat\Tailor\PersistTemplateFinder;
use BapCat\Tailor\Compilers\NomPreprocessor;
use BapCat\Tailor\Compilers\Compiler;

// Grab filesystem directories
$persist = new FilesystemDriver(__DIR__ . '/storage');
$templates = $persist->get('templates');
$compiled  = $persist->get('compiled');

// TemplateFinders are able to find and use raw/compiled templates
$finder = new PersistTemplateFinder($templates, $compiled);

$preprocessor = new NomPreprocessor();

// Compilers translate raw templates into compiled ones
$compiler = new Compiler();

// Create an instance of Tailor to actually do the autoloading
$tailor = new Tailor($finder, $preprocessor, $compiler);

$tailor->bind(Nom::class, 'Nom.nom', ['hello' => 'Hello', 'special' => '<>&', 'arr' => ['a', 'b', 'c'], 'bool' => true]);

var_dump(new Nom());
