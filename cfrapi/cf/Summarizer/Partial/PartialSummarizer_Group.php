<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Util\HtmlUtil;

class PartialSummarizer_Group implements PartialSummarizerInterface {

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
    SummaryHelperInterface $helper
  ) {
    if (!$schema instanceof CfSchema_GroupInterface) {
      return $helper->unknownSchema();
    }

    if (!is_array($conf)) {
      $conf = [];
    }

    $labels = $schema->getLabels();

    $html = '';
    foreach ($schema->getItemSchemas() as $key => $itemSchema) {
      $itemConf = array_key_exists($key, $conf) ? $conf[$key] : NULL;
      $itemSummary = $helper->schemaConfGetSummary($itemSchema, $itemConf);
      $itemLabelUnsafe = isset($labels[$key]) ? $labels[$key] : $key;
      $itemLabelSafe = HtmlUtil::sanitize($itemLabelUnsafe);
      $html .= "<li>$itemLabelSafe: $itemSummary</li>";
    }

    if ('' === $html) {
      return NULL;
    }

    return "<ul>$html</ul>";
  }
}
