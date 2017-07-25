<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Util\StaUtil;

class Summarizer_Group implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Summarizer\SummarizerInterface[]
   */
  private $itemSummarizers;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\Summarizer_Group|null
   */
  public static function create(CfSchema_GroupInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    /** @var \Donquixote\Cf\Summarizer\SummarizerInterface[] $itemSummarizers */
    $itemSummarizers = StaUtil::getMultiple(
      $schema->getItemSchemas(),
      $schemaToAnything,
      SummarizerInterface::class);

    if (NULL === $itemSummarizers) {
      return NULL;
    }

    return new self($schema, $itemSummarizers);
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Donquixote\Cf\Summarizer\SummarizerInterface[] $itemSummarizers
   */
  public function __construct(CfSchema_GroupInterface $schema, array $itemSummarizers) {
    $this->schema = $schema;
    $this->itemSummarizers = $itemSummarizers;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return null|string
   */
  public function confGetSummary($conf, TranslatorInterface $translator) {

    if (!is_array($conf)) {
      $conf = [];
    }

    $labels = $this->schema->getLabels();

    $html = '';
    foreach ($this->itemSummarizers as $key => $itemSummarizer) {

      $itemConf = isset($conf[$key])
        ? $conf[$key]
        : NULL;

      $itemSummary = $itemSummarizer->confGetSummary($itemConf, $translator);

      $itemLabelUnsafe = isset($labels[$key])
        ? $labels[$key]
        : $key;

      $itemLabelSafe = HtmlUtil::sanitize($itemLabelUnsafe);

      $html .= "<li>$itemLabelSafe: $itemSummary</li>";
    }

    if ('' === $html) {
      return NULL;
    }

    return "<ul>$html</ul>";
  }
}
