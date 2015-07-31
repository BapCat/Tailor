<?php namespace BapCat\Tailor;

use BapCat\Interfaces\Persist\Directory;

class PersistTemplateFinder implements TemplateFinder {
  private $templates;
  private $compiled;
  
  public function __construct(Directory $templates, Directory $compiled) {
    $this->templates = $templates;
    $this->compiled  = $compiled;
  }
  
  public function hasCompiled($hash) {
    return $this->compiled->child["$hash.php"]->exists;
  }
  
  public function includeCompiled($hash) {
    include $this->compiled->child["$hash.php"]->full_path;
  }
  
  public function cacheCompiled($hash, $compiled) {
    $file = $this->compiled->child["$hash.php"];
    
    //@TODO
    return file_put_contents($file->full_path, $compiled);
  }
  
  public function hasTemplate($class) {
    return $this->templates->child["$class.php"]->exists;
  }
  
  public function getTemplate($class) {
    $file = $this->templates->child["$class.php"];
    return $file->full_path;
  }
  
  public function getTemplateModified($class) {
    return $this->templates->child["$class.php"]->modified;
  }
}
