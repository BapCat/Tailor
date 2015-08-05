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
    $this->bindings[$alias] = [
      $template,
      $params,
      $this->makeTemplateHash($template, $params)
    ];
  }
  
  private function makeTemplateHash($template, $params) {
    return sha1($template . json_encode($params) . $this->finder->getTemplateModified($template));
  }
  
  private function make($alias) {
    if(!array_key_exists($alias, $this->bindings)) {
      return;
    }
    
    list($template, $params, $hash) = $this->bindings[$alias];
    
    if($this->finder->hasCompiled($hash)) {
      $this->finder->includeCompiled($hash);
      return;
    }
    
    if(!$this->finder->hasTemplate($template)) {
      return;
    }
    
    $path = $this->finder->getTemplate($template);
    $compiled = $this->compiler->compile($path, $params);
    
    $this->finder->cacheCompiled($hash, $compiled);
    $this->finder->includeCompiled($hash);
  }
}
