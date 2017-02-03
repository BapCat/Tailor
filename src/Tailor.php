<?php namespace BapCat\Tailor;

use BapCat\Hashing\Hasher;
use BapCat\Nom\Pipeline;
use BapCat\Persist\Directory;

class Tailor {
  /**
   * @var  Generator  $generator
   */
  private $generator;
  
  /**
   * @var  $bindings  array
   */
  private $bindings = [];
  
  /**
   * @param  Directory  $templates
   * @param  Directory  $cache
   * @param  Pipeline   $pipeline
   * @param  Hasher     $hasher
   */
  public function __construct(Directory $templates, Directory $cache, Pipeline $pipeline, Hasher $hasher) {
    $this->generator = new Generator($templates, $cache, $pipeline, $hasher);
    
    spl_autoload_register([$this, 'make']);
  }
  
  /**
   * May be useful for pre-generating classes (see Remodel)
   * 
   * @return  Generator
   */
  public function getGenerator() {
    return $this->generator;
  }
  
  /**
   * Bind a class to a template with specified parameters
   * 
   * @param  string  $alias
   * @param  string  $template
   * @param  array   $params    (optional)
   * 
   * @return  void
   */
  public function bind($alias, $template, array $params = []) {
    $this->bindCallback($alias, function(Generator $generator) use($template, $params) {
      $compiled = $generator->generate($template, $params);
      $generator->includeFile($compiled);
    });
  }
  
  /**
   * Bind a callback to be executed when the specified class is autoloaded
   * 
   * @param  string    $alias
   * @param  callable  $generator
   * 
   * @return  void
   */
  public function bindCallback($alias, callable $generator) {
    $this->bindings[$alias] = $generator;
  }
  
  /**
   * Autoloader
   * 
   * @param  string  $alias
   * 
   * @return  object
   */
  private function make($alias) {
    if(!array_key_exists($alias, $this->bindings)) {
      return;
    }
    
    $this->bindings[$alias]($this->generator);
  }
}
