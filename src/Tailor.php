<?php namespace BapCat\Tailor;

use BapCat\Hashing\Hasher;
use BapCat\Nom\Pipeline;
use BapCat\Persist\Directory;

class Tailor {
  private $generator;
  private $bindings = [];
  
  public function __construct(Directory $templates, Directory $cache, Pipeline $pipeline, HashGenerationStrategy $hasher = null) {
    $this->generator = new Generator($templates, $cache, $pipeline, $hasher);
    
    spl_autoload_register([$this, 'make']);
  }
  
  public function bind($alias, $template, array $params = []) {
    $this->bindCallback($alias, function(Generator $generator) use($template, $params) {
      $compiled = $generator->generate($template, $params);
      $generator->includeFile($compiled);
    });
  }
  
  public function bindCallback($alias, callable $generator) {
    $this->bindings[$alias] = $generator;
  }
  
  private function make($alias) {
    if(!array_key_exists($alias, $this->bindings)) {
      return;
    }
    
    $this->bindings[$alias]($this->generator);
  }
}
