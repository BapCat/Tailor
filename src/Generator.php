<?php namespace BapCat\Tailor;

use BapCat\Hashing\Hasher;
use BapCat\Nom\Pipeline;
use BapCat\Persist\Directory;
use BapCat\Persist\File;

class Generator {
  private $templates;
  private $cache;
  private $pipeline;
  private $hasher;
  
  public function __construct(Directory $templates, Directory $cache, Pipeline $pipeline, Hasher $hasher) {
    $this->templates = $templates;
    $this->cache     = $cache;
    $this->pipeline  = $pipeline;
    $this->hasher    = $hasher;
  }
  
  public function generate($template, array $params = []) {
    $template = $this->templates->child["$template.php"];
    $hash     = $this->makeTemplateHash($template, $params);
    
    $compiled_file = $this->cache->child["$hash.php"];
    
    if(!$compiled_file->exists) {
      $compiled_code = $this->pipeline->compile($template, $params);
      $compiled_file = $this->cache->child["$hash.php"];
      $compiled_file->writeAll($compiled_code);
    }
    
    return $compiled_file;
  }
  
  public function include(File $compiled) {
    if((include $compiled->makeLocal()->full_path) == false) {
      //@TODO
      throw new \Exception("Could not include [{$compiled->path}]");
    }
  }
  
  private function makeTemplateHash(File $template, array $params) {
    return $template->name . '.' . $this->hasher->make($template->path . json_encode($params) . $template->modified);
  }
}
