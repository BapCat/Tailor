<?php

use BapCat\Persist\Drivers\Filesystem\FilesystemDriver;
use BapCat\Tailor\PersistTemplateFinder;

class PersistTemplateFinderTest extends PHPUnit_Framework_TestCase {
  private $driver;
  private $templates;
  private $compiled;
  private $finder;
  
  public function setUp() {
    $this->stubs = new FilesystemDriver(__DIR__ . '/stubs');
    $this->templates = $this->stubs->get('/');
    $this->compiled  = $this->stubs->get('/');
    
    $this->finder = new PersistTemplateFinder($this->templates, $this->compiled);
  }
  
  public function testParamNotDefined() {
    
  }
}
