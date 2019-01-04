<?php declare(strict_types=1); namespace BapCat\Tailor;

use BapCat\Hashing\Hash;
use BapCat\Persist\File;

interface HashGenerationStrategy {
  /**
   * Generate a unique hash of a template and its parameters
   *
   * @param  File   $template
   * @param  array  $params
   *
   * @return  Hash
   */
  public function generateHash(File $template, array $params): Hash;
}
