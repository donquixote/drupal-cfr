<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\StaUtil;

class Summarizer_Sequence implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  private $itemSummarizer;

  /**
   * @var \Donquixote\Cf\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return \Donquixote\Cf\Summarizer\Summarizer_Sequence|null
   */
  public static function create(
    CfSchema_SequenceInterface $schema,
    SchemaToAnythingInterface $schemaToAnything,
    TranslatorInterface $translator
  ) {

    $itemSummarizer = StaUtil::summarizer($schema->getItemSchema(), $schemaToAnything);

    if (NULL === $itemSummarizer) {
      return NULL;
    }

    return new self($itemSummarizer, $translator);
  }

  /**
   * @param \Donquixote\Cf\Summarizer\SummarizerInterface $itemSummarizer
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(SummarizerInterface $itemSummarizer, TranslatorInterface $translator) {
    $this->itemSummarizer = $itemSummarizer;
    $this->translator = $translator;
  }

  /**
   * @param mixed $conf
   *
   * @return null|string
   */
  public function confGetSummary($conf) {

    if (!is_array($conf)) {
      $conf = [];
    }

    $summary = '';
    foreach ($conf as $delta => $itemConf) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return '- ' . $this->translator->translate('Noisy configuration') . ' -';
      }

      $itemSummary = $this->itemSummarizer->confGetSummary($itemConf);

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
