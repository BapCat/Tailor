<?php namespace BapCat\Tailor\Compilers;

interface Compiler {
  public function compile($_bap_path, array $_bap_data);
}