<?php

namespace Drupal\cfrapi\Configurator\Optional;

use /** @noinspection PhpDeprecationInspection */
  Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

/**
 * @deprecated
 */
class OptionalConfigurator_FromOptionable implements OptionalConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface
   */
  private $optionableConfigurator;

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface $optionableConfigurator
   */
  public function __construct(
    /** @noinspection PhpDeprecationInspection */
    OptionableConfiguratorInterface $optionableConfigurator
  ) {
    $this->optionableConfigurator = $optionableConfigurator;
    $this->emptyness = $optionableConfigurator->getEmptyness();
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    return $this->optionableConfigurator->confGetOptionalForm($conf, $label);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->emptyness->confIsEmpty($conf)
      ? $this->optionableConfigurator->getEmptySummary()
      : $this->optionableConfigurator->confGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    return $this->emptyness->confIsEmpty($conf)
      ? $this->optionableConfigurator->getEmptyValue()
      : $this->optionableConfigurator->confGetValue($conf);
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  public function getEmptyness() {
    return $this->emptyness;
  }
}
