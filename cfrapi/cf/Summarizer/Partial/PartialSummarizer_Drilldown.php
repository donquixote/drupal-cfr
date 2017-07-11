<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\HtmlUtil;

/**
 * @Cf
 */
class PartialSummarizer_Drilldown implements PartialSummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   */
  public function __construct(CfSchema_DrilldownInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return null|string
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper) {

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      return '- ' . $helper->translate('None') . ' -';
    }

    if (NULL === $subSchema = $this->schema->idGetSchema($id)) {
      return '- ' . $helper->translate('Unknown id') . ' -';
    }

    if (NULL === $idLabelUnsafe = $this->schema->idGetLabel($id)) {
      return '- ' . $helper->translate('Unknown id') . ' -';
    }

    $idLabelSafe = HtmlUtil::sanitize($idLabelUnsafe);

    $subSummary = $helper->schemaConfGetSummary($subSchema, $subConf);

    if (!is_string($subSummary) || '' === $subSummary) {
      return $idLabelSafe;
    }

    return $idLabelSafe . ': ' . $subSummary;
  }
}
