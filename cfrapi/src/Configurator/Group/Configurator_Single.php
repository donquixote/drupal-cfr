<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_Single implements GroupConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $configurator;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   */
  function __construct(ConfiguratorInterface $configurator) {
    $this->configurator = $configurator;
  }

  /**
   * Builds the value based on the given configuration.
   *
   * @param mixed[]|mixed $conf
   *
   * @return mixed[]|\Drupal\cfrapi\BrokenValue\BrokenValueInterface
   */
  function confGetValue($conf) {
    $value = $this->configurator->confGetValue($conf);
    if ($value instanceof BrokenValueInterface) {
      return new BrokenValue($this, get_defined_vars(), 'Value is broken.');
    }
    return [$value];
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->configurator->confGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   *   A form element(s) array.
   */
  function confGetForm($conf, $label) {
    return $this->configurator->confGetForm($conf, $label);
  }

}
