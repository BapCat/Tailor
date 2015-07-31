<?php namespace BapCat\Tailor;

use BapCat\Interfaces\Persist\Directory;

class PersistTemplateFinder implements TemplateFinder {
  private $templates;
  private $compiled;
  
  public function __construct(Directory $templates, Directory $compiled) {
    $this->templates = $templates;
    $this->compiled  = $compiled;
  }
  
  private function aliasToFile($alias) {
    return str_replace('\\', '.', $alias);
  }
  
  public function hasCompiled($hash) {
    return $this->compiled->child["$hash.php"]->exists;
  }
  
  public function includeCompiled($hash) {
    include $this->compiled->driver->getRoot() . '/' . $this->compiled->path . "/$hash.php";
  }
  
  public function cacheCompiled($hash, $compiled) {
    $file = $this->compiled->child["$hash.php"];
    
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
  
  public function getTemplateModified($class) {
    return filemtime($this->getTemplate($class));
  }
}
