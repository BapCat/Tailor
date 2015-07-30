<?php namespace BapCat\Tailor;

use BapCat\Interfaces\Persist\Directory;
use BapCat\Interfaces\Persist\File;

use Illuminate\View\Compilers\BladeCompiler;

class Tailor {
  private $blade;
  
  private $templates;
  private $compiled;
  
  public function __construct(BladeCompiler $blade, Directory $templates, Directory $compiled) {
    $this->blade = $blade;
    
    $this->templates = $templates;
    $this->compiled  = $compiled;
    
    spl_autoload_register([$this, 'make']);
  }
  
  public function bind($class, array $config) {
    
  }
  
  private function make($class) {
    $file = $this->templates->child["$class.blade.php"];
    
    if($file->exists) {
      //@TODO
      $fn = $file->driver->getRoot() . '/' . $file->path;
      $content = file_get_contents($fn);
      
      var_dump($this->blade->compileString($content));
    }
  }
}
