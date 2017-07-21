<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Util\StaUtil;

/**
 * @Cf
 */
class SummarizerP2_Drilldown implements SummarizerP2Interface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(CfSchema_DrilldownInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    $this->schema = $schema;
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return null|string
   */
  public function confGetSummary($conf, TranslatorInterface $translator) {

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      return '- ' . $translator->translate('None') . ' -';
    }

    if (NULL === $subSchema = $this->schema->idGetSchema($id)) {
      return '- ' . $translator->translate('Unknown id "@id".', ['@id' => $id]) . ' -';
    }

    if (NULL === $idLabelUnsafe = $this->schema->idGetLabel($id)) {
      return '- ' . $translator->translate('Unknown id "@id".', ['@id' => $id]) . ' -';
    }

    $idLabelSafe = HtmlUtil::sanitize($idLabelUnsafe);

    $subSummarizer = StaUtil::summarizerP2($subSchema, $this->schemaToAnything);

    if (NULL === $subSummarizer) {
      return '- ' . $translator->translate('Unknown id "@id".', ['@id' => $id]) . ' -';
    }

    $subSummary = $subSummarizer->confGetSummary($subConf, $translator);

    if (!is_string($subSummary) || '' === $subSummary) {
      return $idLabelSafe;
    }

    return $idLabelSafe . ': ' . $subSummary;
  }
}
