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
  
  public function bind($alias, $template, array $params) {
    $this->bindings[$alias] = [$template, $params];
  }
  
  private function make($alias) {
    list($template, $params) = array_key_exists($alias, $this->bindings) ? $this->bindings[$alias] : [];
    
    $hash = sha1(json_encode($params));
    
    if($this->finder->hasCompiled($alias, $hash)) {
      $this->finder->includeCompiled($alias, $hash);
      return;
    }
    
    if(!$this->finder->hasTemplate($template)) {
      return;
    }
    
    $path = $this->finder->getTemplate($template);
    $compiled = $this->compiler->compile($path, $params);
    
    $this->finder->cacheCompiled($alias, $hash, $compiled);
    $this->finder->includeCompiled($alias, $hash);
  }
}
