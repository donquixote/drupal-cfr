<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\StaUtil;

/**
 * @Cf
 */
class SummarizerP2_Sequence implements SummarizerP2Interface {

  /**
   * @var \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  private $itemSummarizer;

  /**
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Summarizer\P2\SummarizerP2_Sequence|null
   */
  public static function create(CfSchema_SequenceInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    $itemSummarizer = StaUtil::summarizerP2($schema->getItemSchema(), $schemaToAnything);

    if (NULL === $itemSummarizer) {
      return NULL;
    }

    return new self($itemSummarizer);
  }

  /**
   * @param \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface $itemSummarizer
   */
  public function __construct(SummarizerP2Interface $itemSummarizer) {
    $this->itemSummarizer = $itemSummarizer;
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

    $summary = '';
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return '- ' . $translator->translate('Noisy configuration') . ' -';
      }

      $itemSummary = $this->itemSummarizer->confGetSummary($itemConf, $translator);

      if (is_string($itemSummary) && '' !== $itemSummary) {
        $summary .= "<li>$itemSummary</li>";
      }
    }

    if ('' === $summary) {
      return NULL;
    }

    return "<ol>$summary</ol>";
  }
}
