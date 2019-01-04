<?php declare(strict_types=1); namespace BapCat\Tailor;

use BapCat\Hashing\Hash;
use BapCat\Hashing\Hasher;
use BapCat\Persist\File;

class DefaultHashGenerator implements HashGenerationStrategy {
  /** @var Hasher $hasher */
  private $hasher;

  public function __construct(Hasher $hasher) {
    $this->hasher = $hasher;
  }

  /**
   * {@inheritdoc}
   */
  public function generateHash(File $template, array $params): Hash {
    return $this->hasher->make($template->path . json_encode($params) . $template->modified);
  }
}
