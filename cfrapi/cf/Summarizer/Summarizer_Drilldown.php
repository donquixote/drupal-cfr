<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Util\StaUtil;

/**
 * @Cf
 */
class Summarizer_Drilldown implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @var \Donquixote\Cf\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(
    CfSchema_DrilldownInterface $schema,
    SchemaToAnythingInterface $schemaToAnything,
    TranslatorInterface $translator
  ) {
    $this->schema = $schema;
    $this->schemaToAnything = $schemaToAnything;
    $this->translator = $translator;
  }

  /**
   * @param mixed $conf
   *
   * @return null|string
   */
  public function confGetSummary($conf) {

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      return '- ' . $this->translator->translate('None') . ' -';
    }

    if (NULL === $subSchema = $this->schema->idGetSchema($id)) {
      return '- ' . $this->translator->translate('Unknown id "@id".', ['@id' => $id]) . ' -';
    }

    if (NULL === $idLabelUnsafe = $this->schema->idGetLabel($id)) {
      return '- ' . $this->translator->translate('Unknown id "@id".', ['@id' => $id]) . ' -';
    }

    $idLabelSafe = HtmlUtil::sanitize($idLabelUnsafe);

    $subSummarizer = StaUtil::summarizer($subSchema, $this->schemaToAnything);

    if (NULL === $subSummarizer) {
      return '- ' . $this->translator->translate('Unknown id "@id".', ['@id' => $id]) . ' -';
    }

    $subSummary = $subSummarizer->confGetSummary($subConf);

    if (!is_string($subSummary) || '' === $subSummary) {
      return $idLabelSafe;
    }

    return $idLabelSafe . ': ' . $subSummary;
  }
}
