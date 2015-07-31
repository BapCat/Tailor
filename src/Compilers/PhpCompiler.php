<?php namespace BapCat\Tailor\Compilers;

class PhpCompiler implements Compiler {
  public function compile($_bap_path, array $_bap_data) {
    $_bap_level = ob_get_level();
    ob_start();
    
    extract($_bap_data);
    
    //try {
      include $_bap_path;
    /*} catch(Exception $e) {
      $this->handleViewException($e, $__level);
    } catch(Throwable $e) {
      $this->handleViewException(new FatalThrowableError($e), $__level);
    }*/
    
    return ltrim(ob_get_clean());
  }
}