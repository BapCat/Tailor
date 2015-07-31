<?php namespace BapCat\Tailor;

interface TemplateFinder {
  public function hasCompiled($hash);
  public function includeCompiled($hash);
  public function cacheCompiled($hash, $compiled);
  public function hasTemplate($class);
  public function getTemplate($class);
  public function getTemplateModified($class);
}
