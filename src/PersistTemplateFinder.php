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
  
  public function hasCompiled($alias, $hash) {
    return $this->compiled->child[$this->aliasToFile($alias) . ".$hash.php"]->exists;
  }
  
  public function includeCompiled($alias, $hash) {
    include $this->compiled->driver->getRoot() . '/' . $this->compiled->path . '/' . $this->aliasToFile($alias) . ".$hash.php";
  }
  
  public function cacheCompiled($alias, $hash, $compiled) {
    $file = $this->compiled->child[$this->aliasToFile($alias) . ".$hash.php"];
    
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
