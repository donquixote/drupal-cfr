<?php

namespace Drupal\cfrapi\SummaryBuilder;

use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\SummaryBuilder\Group\SummaryBuilderGroup_Static;
use Drupal\cfrapi\SummaryBuilder\Inline\SummaryBuilderInline_Static;

class SummaryBuilder_Static implements SummaryBuilderInterface {

  /**
   * @param string $label
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $optionsConfToSummary
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  public function idConf($label, ConfToSummaryInterface $optionsConfToSummary = NULL, $optionsConf) {

    if (NULL !== $optionsConfToSummary) {
      $optionsConfSummary = $optionsConfToSummary->confGetSummary($optionsConf, $this);

      if (is_string($optionsConfSummary) && '' !== $optionsConfSummary) {
        return check_plain($label) . ': ' . $optionsConfSummary;
      }
    }

    return check_plain($label);
  }

  /**
   * Starts a group of named settings.
   *
   * @return \Drupal\cfrapi\SummaryBuilder\Group\SummaryBuilderGroupInterface
   */
  public function startGroup() {
    return new SummaryBuilderGroup_Static($this);
  }

  /**
   * Starts a group of unnamed settings.
   *
   * @return \Drupal\cfrapi\SummaryBuilder\Inline\SummaryBuilderInlineInterface
   */
  public function startInline() {
    return new SummaryBuilderInline_Static($this);
  }

  /**
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param array $confItems
   *
   * @return mixed
   */
  public function buildSequence(ConfToSummaryInterface $confToSummary, array $confItems) {
    $summary = '';
    foreach ($confItems as $delta => $deltaConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return '- ' . t('Noisy configuration') . ' -';
      }
      $deltaSummary = $confToSummary->confGetSummary($deltaConf, $this);
      if (is_string($deltaSummary) && '' !== $deltaSummary) {
        $summary .= '<li>' . $deltaSummary . '</li>';
      }
    }
    return '' !== $summary
      ? '<ol>' . $summary . '</ol>'
      : NULL;
  }
}
