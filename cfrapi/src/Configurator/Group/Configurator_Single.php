<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
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
  public function __construct(ConfiguratorInterface $configurator) {
    $this->configurator = $configurator;
  }

  /**
   * Builds the value based on the given configuration.
   *
   * @param mixed[]|mixed $conf
   *
   * @return mixed[]
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    $value = $this->configurator->confGetValue($conf);
    return [$value];
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->configurator->confGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   *   A form element(s) array.
   */
  public function confGetForm($conf, $label) {
    return $this->configurator->confGetForm($conf, $label);
  }

  /**
   * Allows this to be subclassed implementing GroupConfiguratorInterface.
   *
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string[]
   *   PHP statements to generate the values.
   *
   * @see GroupConfiguratorInterface::confGetPhpStatements()
   */
  public function confGetPhpStatements($conf, CfrCodegenHelperInterface $helper) {
    $php = $this->configurator->confGetPhp($conf, $helper);
    return array($php);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    $php = $this->configurator->confGetPhp($conf, $helper);
    return "[$php]";
  }
}
