<?php namespace BapCat\Tailor;

use BapCat\Hashing\Hasher;
use BapCat\Nom\Pipeline;
use BapCat\Persist\Directory;
use BapCat\Persist\File;

class Tailor {
  private $templates;
  private $cache;
  private $pipeline;
  private $hasher;
  
  private $bindings = [];
  
  public function __construct(Directory $templates, Directory $cache, Pipeline $pipeline, Hasher $hasher) {
    $this->templates = $templates;
    $this->cache     = $cache;
    $this->pipeline  = $pipeline;
    $this->hasher    = $hasher;
    
    spl_autoload_register([$this, 'make']);
  }
  
  public function bind($alias, $template, array $params = []) {
    $template = $this->templates->child["$template.php"];
    
    if(!$template->exists) {
      //@TODO: proper exception
      throw new \Exception("Template [{$template->path}] doesn't exist!");
    }
    
    $this->bindings[$alias] = [
      $template,
      $params,
      $this->makeTemplateHash($template, $params)
    ];
  }
  
  private function makeTemplateHash(File $template, array $params) {
    return $template->name . '.' . $this->hasher->make($template->path . json_encode($params) . $template->modified);
  }
  
  private function make($alias) {
    if(!array_key_exists($alias, $this->bindings)) {
      return;
    }
    
    list($template, $params, $hash) = $this->bindings[$alias];
    
    $compiled_file = $this->cache->child["$hash.php"];
    
    if(!$compiled_file->exists) {
      $compiled_code = $this->pipeline->compile($template, $params);
      $compiled_file = $this->cache->child["$hash.php"];
      $compiled_file->writeAll($compiled_code);
    }
    
    if((include $compiled_file->makeLocal()->full_path) == false) {
      //@TODO
      throw new \Exception("Could not include [{$compiled_file->path}]");
    }
  }
}
