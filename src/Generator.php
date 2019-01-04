<?php declare(strict_types=1); namespace BapCat\Tailor;

use BapCat\Hashing\Algorithms\Sha1WeakHasher;
use BapCat\Nom\Pipeline;
use BapCat\Nom\TemplateNotFoundException;
use BapCat\Persist\Directory;
use BapCat\Persist\File;
use BapCat\Persist\NotAFileException;

class Generator {
  /** @var Directory $templates */
  private $templates;

  /** @var Directory $cache */
  private $cache;

  /** @var Pipeline $pipeline */
  private $pipeline;

  /** @var DefaultHashGenerator $hasher */
  private $hasher;

  public function __construct(Directory $templates, Directory $cache, Pipeline $pipeline, ?HashGenerationStrategy $hasher = null) {
    $this->templates = $templates;
    $this->cache     = $cache;
    $this->pipeline  = $pipeline;
    $this->hasher    = $hasher ?: new DefaultHashGenerator(new Sha1WeakHasher());
  }

  /**
   * @param  string   $template
   * @param  mixed[]  $params
   *
   * @return  File  The cached compiled template
   *
   * @throws  TemplateNotFoundException
   * @throws  NotAFileException
   */
  public function generate(string $template, array $params = []): File {
    $template = $this->templates->child["$template.php"];

    if(!($template instanceof File)) {
      throw new NotAFileException($template->path);
    }

    $hash = $this->makeTemplateHash($template, $params);

    $compiled_file = $this->cache->child["$hash.php"];

    if(!$compiled_file->exists) {
      $compiled_code = $this->pipeline->compile($template, $params);
      $compiled_file = $this->cache->child["$hash.php"];
      $compiled_file->writeAll($compiled_code);
    }

    return $compiled_file;
  }

  public function includeFile(File $compiled): void {
    if((include $compiled->makeLocal()->full_path) === false) {
      //@TODO
      throw new \Exception("Could not include [{$compiled->path}]");
    }
  }

  private function makeTemplateHash(File $template, array $params): string {
    return $template->name . '.' . $this->hasher->generateHash($template, $params);
  }
}
