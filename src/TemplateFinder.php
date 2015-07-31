<?php namespace BapCat\Tailor;

interface TemplateFinder {
  public function hasCompiled($alias, $hash);
  public function includeCompiled($alias, $hash);
  public function cacheCompiled($alias, $hash, $compiled);
  public function hasTemplate($class);
  public function getTemplate($class);
}
