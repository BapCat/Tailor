<?php

use BapCat\Persist\Drivers\Local\LocalDriver;
use BapCat\Persist\PathNotFoundException;
use BapCat\Tailor\PersistTemplateFinder;

class PersistTemplateFinderTest extends PHPUnit_Framework_TestCase {
  private $driver;
  private $templates;
  private $compiled;
  private $finder;
  
  public function setUp() {
    $this->stubs = new LocalDriver(__DIR__ . '/storage');
    $this->templates = $this->stubs->getDirectory('templates');
    $this->compiled  = $this->stubs->getDirectory('compiled');
    
    $this->finder = new PersistTemplateFinder($this->templates, $this->compiled);
  }
  
  public function testHasTemplate() {
    $this->assertTrue($this->finder->hasTemplate('Template'));
    $this->assertFalse($this->finder->hasTemplate('DoesntExist'));
  }
  
  public function testGetTemplate() {
    $full_path = $this->finder->getTemplate('Template');
    $this->assertEquals(__DIR__ . '/storage/templates/Template.php', $full_path);
  }
  
  public function testGetModified() {
    // Not really a great test :/
    $this->assertInternalType('int', $this->finder->getTemplateModified('Template'));
  }
  
  public function testGetModifiedDoesntExist() {
    $this->setExpectedException(PathNotFoundException::class);
    $time = $this->finder->getTemplateModified('DoesntExist');
  }
  
  public function testHasCompiled() {
    $this->assertTrue($this->finder->hasCompiled('abc'));
    $this->assertFalse($this->finder->hasCompiled('DoesntExist'));
  }
  
  public function testIncludeCompiled() {
    $this->finder->includeCompiled('abc');
    $abc = new Abc();
    $this->assertEquals('Abc', $abc->doIt());
  }
  
  public function testCacheCompiled() {
    $this->finder->cacheCompiled('compiled', 'This is a test');
    $contents = file_get_contents(__DIR__ . '/storage/compiled/compiled.php');
    $this->assertEquals('This is a test', $contents);
  }
}
