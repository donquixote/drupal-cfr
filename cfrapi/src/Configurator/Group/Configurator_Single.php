<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpUtil;
use Drupal\cfrapi\GroupConfToPhpStatements\GroupConfToPhpStatementsInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_Single implements GroupConfiguratorInterface, GroupConfToPhpStatementsInterface, ConfToPhpInterface {

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
   * @return mixed[]|\Drupal\cfrapi\BrokenValue\BrokenValueInterface
   */
  public function confGetValue($conf) {
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
   * @param mixed $conf
   *
   * @return string[]
   *   PHP statements to generate the values.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetPhpStatements($conf) {
    $php = ConfToPhpUtil::objConfGetPhp($this->configurator, $conf);
    return array($php);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  public function confGetPhp($conf) {
    $php = ConfToPhpUtil::objConfGetPhp($this->configurator, $conf);
    return 'array(' . $php . ')';
  }
}
