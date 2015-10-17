<?php namespace BapCat\Tailor\Compilers;

use BapCat\Tailor\TemplateFinder;

class NullPreprocessor implements Preprocessor {
  public function process($path, TemplateFinder $finder) {
    return $path;
  }
}
