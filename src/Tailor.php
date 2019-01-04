<?php declare(strict_types=1); namespace BapCat\Tailor;

use BapCat\Nom\Pipeline;
use BapCat\Persist\Directory;

class Tailor {
  /** @var  Generator  $generator */
  private $generator;

  /** @var  callable[]  $bindings */
  private $bindings = [];

  /**
   * @param  Directory                    $templates
   * @param  Directory                    $cache
   * @param  Pipeline                     $pipeline
   * @param  HashGenerationStrategy|null  $hasher
   */
  public function __construct(Directory $templates, Directory $cache, Pipeline $pipeline, ?HashGenerationStrategy $hasher = null) {
    $this->generator = new Generator($templates, $cache, $pipeline, $hasher);

    spl_autoload_register([$this, 'make']);
  }

  /**
   * May be useful for pre-generating classes (see Remodel)
   *
   * @return  Generator
   */
  public function getGenerator(): Generator {
    return $this->generator;
  }

  /**
   * Bind a class to a template with specified parameters
   *
   * @param  string   $alias
   * @param  string   $template
   * @param  mixed[]  $params    (optional)
   *
   * @return  void
   */
  public function bind(string $alias, string $template, array $params = []): void {
    $this->bindCallback($alias, function(Generator $generator) use($template, $params): void {
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
  public function bindCallback(string $alias, callable $generator): void {
    $this->bindings[$alias] = $generator;
  }

  /**
   * Autoloader
   *
   * @param  string  $alias
   *
   * @return  void
   */
  private function make(string $alias): void {
    if(!array_key_exists($alias, $this->bindings)) {
      return;
    }

    $this->bindings[$alias]($this->generator);
  }
}
