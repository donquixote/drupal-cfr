<?php

namespace Drupal\cfrapi\Configurator\Unconfigurable;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SometimesConfigurable\PossiblyUnconfigurableInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

abstract class UnconfigurableConfiguratorBase implements ConfiguratorInterface, PossiblyUnconfigurableInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    return [];
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return NULL;
  }

  /**
   * @return bool
   */
  public function isConfigurable() {
    return FALSE;
  }
}
