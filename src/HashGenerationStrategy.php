<?php namespace BapCat\Tailor;

use BapCat\Persist\File;

interface HashGenerationStrategy {
  public function generateHash(File $template, array $params);
}
