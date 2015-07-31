<?php

use BapCat\Tailor\Compilers\PhpCompiler;

class PhpCompilerTest extends PHPUnit_Framework_TestCase {
  private $compiler;
  private $stubs;
  
  private $compiled_simple =
'<?php
class Simple {
  public function test() {
    return \'test\';
  }
}
';
  
  private $compiled_conditional =
'<?php
class Conditional {
  public function test() {
    return \'test\';
  }
}
';
  
  private $compiled_loops =
'<?php
class Loops {
  public function test0() {
    return \'test0\';
  }
  public function test1() {
    return \'test1\';
  }
  public function test2() {
    return \'test2\';
  }
}
';
  
  public function setUp() {
    $this->compiler = new PhpCompiler();
    $this->stubs = __DIR__ . '/../stubs';
  }
  
  public function testParamNotDefined() {
    $this->setExpectedException(PHPUnit_Framework_Error_Notice::class);
    
    $this->compiler->compile("{$this->stubs}/Simple.php");
  }
  
  public function testSimple() {
    $compiled = $this->compiler->compile("{$this->stubs}/Simple.php", ['fn' => 'test']);
    $this->assertEquals($this->compiled_simple, $compiled);
  }
  
  public function testWithConditionals() {
    $compiled = $this->compiler->compile("{$this->stubs}/Conditional.php", ['do_it' => true]);
    $this->assertEquals($this->compiled_conditional, $compiled);
  }
  
  public function testWithLoops() {
    $compiled = $this->compiler->compile("{$this->stubs}/Loops.php", ['amount' => 3]);
    $this->assertEquals($this->compiled_loops, $compiled);
  }
}
