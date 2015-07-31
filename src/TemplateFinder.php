<?php namespace BapCat\Tailor;

interface TemplateFinder {
  public function hasCompiled($class);
  public function includeCompiled($class);
  public function cacheCompiled($class, $compiled);
  public function hasTemplate($class);
  public function getTemplate($class);
}
