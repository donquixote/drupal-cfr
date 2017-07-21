<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Util\StaUtil;

class SummarizerP2_Optional implements SummarizerP2Interface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  private $decorated;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   *
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  public static function create(
    CfSchema_OptionalInterface $schema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    $decorated = StaUtil::summarizerP2($schema->getDecorated(), $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    $emptyness = StaUtil::emptyness($schema, $schemaToAnything);

    if (NULL === $emptyness) {
      return new self($schema, $decorated);
    }

    return new SummarizerP2_OptionalWithEmptyness(
      $schema,
      $decorated,
      $emptyness);
  }

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface $decorated
   */
  public function __construct(
    CfSchema_OptionalInterface $schema,
    SummarizerP2Interface $decorated
  ) {
    $this->schema = $schema;
    $this->decorated = $decorated;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return string
   */
  public function confGetSummary($conf, TranslatorInterface $translator) {

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

    return $this->decorated->confGetSummary($subConf, $translator);
  }
}
