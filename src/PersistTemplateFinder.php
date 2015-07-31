<?php namespace BapCat\Tailor;

use BapCat\Interfaces\Persist\Directory;

class PersistTemplateFinder implements TemplateFinder {
  private $templates;
  private $compiled;
  
  public function __construct(Directory $templates, Directory $compiled) {
    $this->templates = $templates;
    $this->compiled  = $compiled;
  }
  
  public function hasCompiled($class) {
    return $this->compiled->child["$class.php"]->exists;
  }
  
  public function includeCompiled($class) {
    include $this->compiled->driver->getRoot() . '/' . $this->compiled->path . "/$class.php";
  }
  
  public function cacheCompiled($class, $compiled) {
    $file = $this->compiled->child["$class.php"];
    
    //@TODO
    $fn = $file->driver->getRoot() . '/' . $file->path;
    return file_put_contents($fn, $compiled);
  }
  
  public function hasTemplate($class) {
    return $this->templates->child["$class.php"]->exists;
  }
  
  public function getTemplate($class) {
    $file = $this->templates->child["$class.php"];
    
    //@TODO
    return $file->driver->getRoot() . "/{$file->path}";
  }
}
