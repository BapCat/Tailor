<?php namespace BapCat\Tailor;

use BapCat\Tailor\Compilers\Compiler;

class Tailor {
  private $finder;
  private $compiler;
  
  private $bindings = [];
  
  public function __construct(TemplateFinder $finder, Compiler $compiler) {
    $this->finder   = $finder;
    $this->compiler = $compiler;
    
    spl_autoload_register([$this, 'make']);
  }
  
  public function bind($class, array $config) {
    $this->bindings[$class] = $config;
  }
  
  private function make($class) {
    if($this->finder->hasCompiled($class)) {
      $this->finder->includeCompiled($class);
      return;
    }
    
    if(!$this->finder->hasTemplate($class)) {
      return;
    }
    
    $data = array_key_exists($class, $this->bindings) ? $this->bindings[$class] : [];
    
    $path = $this->finder->getTemplate($class);
    $compiled = $this->compiler->compile($path, $data);
    
    $this->finder->cacheCompiled($class, $compiled);
    $this->finder->includeCompiled($class);
  }
}
