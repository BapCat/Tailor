<?php namespace BapCat\Tailor\Compilers;

use BapCat\Tailor\TemplateFinder;

interface Preprocessor {
  public function process($path, TemplateFinder $finder);
}