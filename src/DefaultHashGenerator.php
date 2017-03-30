<?php namespace BapCat\Tailor;

use BapCat\Hashing\Hasher;
use BapCat\Persist\File;

class DefaultHashGenerator implements HashGenerationStrategy {
  private $hasher;
  
  public function __construct(Hasher $hasher) {
    $this->hasher = $hasher;
  }
  
  public function generateHash(File $template, array $params) {
    return $this->hasher->make($template->path . json_encode($params) . $template->modified);
  }
}
