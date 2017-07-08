<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\HtmlUtil;

class PartialSummarizer_Drilldown implements PartialSummarizerInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return null|string
   */
  public function schemaConfGetSummary(
    CfSchemaInterface $schema,
    $conf,
    SummaryHelperInterface $helper)
  {
    if (!$schema instanceof CfSchema_DrilldownInterface) {
      return $helper->unknownSchema();
    }

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      return '- ' . $helper->translate('None') . ' -';
    }

    if (NULL === $subSchema = $schema->idGetSchema($id)) {
      return '- ' . $helper->translate('Unknown id') . ' -';
    }

    if (NULL === $idLabelUnsafe = $schema->idGetLabel($id)) {
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
