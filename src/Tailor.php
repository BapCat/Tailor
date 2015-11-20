<?php namespace BapCat\Tailor;

use BapCat\Hashing\Hasher;
use BapCat\Nom\Compiler;
use BapCat\Nom\Preprocessor;
use BapCat\Persist\Directory;
use BapCat\Persist\File;
use BapCat\Persist\FileReader;

class Tailor {
  private $templates;
  private $compiled;
  private $preprocessor;
  private $compiler;
  private $hasher;
  
  private $bindings = [];
  
  public function __construct(Directory $templates, Directory $compiled, Preprocessor $preprocessor, Compiler $compiler, Hasher $hasher) {
    $this->templates    = $templates;
    $this->compiled     = $compiled;
    $this->preprocessor = $preprocessor;
    $this->compiler     = $compiler;
    $this->hasher       = $hasher;
    
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
    return $this->hasher->make($template->path . json_encode($params) . $template->modified);
  }
  
  private function make($alias) {
    if(!array_key_exists($alias, $this->bindings)) {
      return;
    }
    
    list($template, $params, $hash) = $this->bindings[$alias];
    
    $compiled_file = $this->compiled->child["$hash.php"];
    
    if(!$compiled_file->exists) {
      $code = null;
      $template->read(function(FileReader $reader) use(&$code) {
        $code = $reader->read();
      });
      
      $processed_code = $this->preprocessor->process($code);
      $processed_file = $this->compiled->child["$hash.php"];
      
      //@TODO: use FileWriter when available
      file_put_contents($processed_file->full_path, $processed_code);
      
      //@TODO: this won't work unless it's a LocalFile
      $compiled_code = $this->compiler->compile($processed_file, $params);
      $compiled_file = $this->compiled->child["$hash.php"];
      file_put_contents($compiled_file->full_path, $compiled_code);
    }
    
    if((include $compiled_file->makeLocal()->full_path) == false) {
      //@TODO
      throw new \Exception("Could not include [{$compiled_file->path}]");
    }
  }
}
