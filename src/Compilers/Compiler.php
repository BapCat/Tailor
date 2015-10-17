<?php namespace BapCat\Tailor\Compilers;

/* Most of this code is taken from and owned by Illuminate/View */

use Exception;
use Throwable;

class Compiler {
  public function compile($_bap_path, array $_bap_data = []) {
    $_bap_level = ob_get_level();
    ob_start();
    
    extract($_bap_data);
    
    try {
      require $_bap_path;
    } catch(Exception $e) {
      $this->handleViewException($e, $_bap_level);
    } catch(Throwable $e) {
      //@TODO probably don't want to use Exception
      $this->handleViewException(new Exception($e), $_bap_level);
    }
    
    return ltrim(ob_get_clean());
  }
  
  private function handleViewException(Exception $e, $ob_level) {
    while(ob_get_level() > $ob_level) {
      ob_end_clean();
    }
    
    throw $e;
  }
}
