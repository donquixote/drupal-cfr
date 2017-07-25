<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Util\StaUtil;

class Summarizer_Optional implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  private $decorated;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   *
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  public static function create(
    CfSchema_OptionalInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    $decorated = StaUtil::summarizer($schema->getDecorated(), $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    $emptiness = StaUtil::emptiness($schema, $schemaToAnything);

    if (NULL === $emptiness) {
      return new self($schema, $decorated);
    }

    return new Summarizer_OptionalWithEmptiness(
      $schema,
      $decorated,
      $emptiness);
  }

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Summarizer\SummarizerInterface $decorated
   */
  public function __construct(
    CfSchema_OptionalInterface $schema,
    SummarizerInterface $decorated
  ) {
    $this->schema = $schema;
    $this->decorated = $decorated;
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetSummary($conf) {

    if (!is_array($conf) || empty($conf['enabled'])) {

      if (NULL === $summaryUnsafe = $this->schema->getEmptySummary()) {
        return NULL;
      }

      // The schema's summary might not be designed for HTML.
      return HtmlUtil::sanitize($summaryUnsafe);
    }

    $subConf = isset($conf['options'])
      ? $conf['options']
      : NULL;

    return $this->decorated->confGetSummary($subConf);
  }
}
