<?php namespace BapCat\Tailor;

use BapCat\Interfaces\Persist\Directory;
use Illuminate\View\ViewFinderInterface;

class PersistViewFinder implements ViewFinderInterface {
  private $templates;
  
  public function __construct(Directory $templates) {
    $this->templates = $templates;
  }
  
  /**
   * Get the fully qualified location of the view.
   *
   * @param  string  $view
   * @return string
   */
  public function find($view) {
    return $this->templates->driver->getRoot() . '/' . $this->templates->child[$view]->path;
  }
  
  /**
   * Add a location to the finder.
   *
   * @param  string  $location
   * @return void
   */
  public function addLocation($location) {
    
  }
  
  /**
   * Add a namespace hint to the finder.
   *
   * @param  string  $namespace
   * @param  string|array  $hints
   * @return void
   */
  public function addNamespace($namespace, $hints) {
    
  }
  
  /**
   * Prepend a namespace hint to the finder.
   *
   * @param  string  $namespace
   * @param  string|array  $hints
   * @return void
   */
  public function prependNamespace($namespace, $hints) {
    
  }
  
  /**
   * Add a valid view extension to the finder.
   *
   * @param  string  $extension
   * @return void
   */
  public function addExtension($extension) {
    
  }
}
