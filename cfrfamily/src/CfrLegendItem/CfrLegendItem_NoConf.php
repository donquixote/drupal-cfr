<?php

namespace Drupal\cfrfamily\CfrLegendItem;

use Drupal\cfrapi\LegendItem\LegendItem;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

/**
 * Represents a legend item with no further configuration options.
 */
class CfrLegendItem_NoConf extends LegendItem implements CfrLegendItemInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetForm($conf, $label) {
    return array();
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *   An object that controls the format of the summary.
   *
   * @return mixed|string|null
   *   A string summary is always allowed. But other values may be returned if
   *   $summaryBuilder generates them.
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return NULL;
  }
}
