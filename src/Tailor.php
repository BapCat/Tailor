<?php namespace BapCat\Tailor;

use BapCat\Tailor\Compilers\Compiler;
use BapCat\Tailor\Compilers\Preprocessor;

class Tailor {
  private $finder;
  private $preprocessor;
  private $compiler;
  
  private $bindings = [];
  
  public function __construct(TemplateFinder $finder, Preprocessor $preprocessor, Compiler $compiler) {
    $this->finder       = $finder;
    $this->preprocessor = $preprocessor;
    $this->compiler     = $compiler;
    
    spl_autoload_register([$this, 'make']);
  }
  
  public function bind($alias, $template, array $params = []) {
    if(!$this->finder->hasTemplate($template)) {
      //@TODO: proper exception
      throw new \Exception("Template $template doesn't exist!");
    }
    
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
    
    $path = $this->finder->getTemplate($template);
    $new_path = $this->preprocessor->process($path, $this->finder);
    $compiled = $this->compiler->compile($new_path, $params);
    
    $this->finder->cacheCompiled($hash, $compiled);
    $this->finder->includeCompiled($hash);
  }
}
