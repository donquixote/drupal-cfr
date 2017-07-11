<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Util\HtmlUtil;

/**
 * @Cf
 */
class PartialSummarizer_Group implements PartialSummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   */
  public function __construct(CfSchema_GroupInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return null|string
   */
  public function schemaConfGetSummary($conf, SummaryHelperInterface $helper) {

    if (!is_array($conf)) {
      $conf = [];
    }

    $labels = $this->schema->getLabels();

    $html = '';
    foreach ($this->schema->getItemSchemas() as $key => $itemSchema) {

      $itemConf = isset($conf[$key])
        ? $conf[$key]
        : NULL;

      $itemSummary = $helper->schemaConfGetSummary($itemSchema, $itemConf);

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
