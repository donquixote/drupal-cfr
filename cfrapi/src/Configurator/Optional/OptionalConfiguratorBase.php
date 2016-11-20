<?php

namespace Drupal\cfrapi\Configurator\Optional;

use Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface;
use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

abstract class OptionalConfiguratorBase implements OptionalConfiguratorInterface, ConfEmptynessInterface {

  /**
   * @var
   */
  private $required;

  /**
   * @param $required
   */
  public function __construct($required = TRUE) {
    $this->required = $required;
  }

  /**
   * @return bool
   */
  protected function isRequired() {
    return $this->required;
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
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {

    if ($this->confIsEmpty($conf)) {
      if ($this->required) {
        return '- ' . t('Missing') . ' -';
      }

      return '- ' . t('None') . ' -';
    }

    return $this->nonEmptyConfGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return mixed
   */
  abstract protected function nonEmptyConfGetSummary($conf, SummaryBuilderInterface $summaryBuilder);

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {

    if ($this->confIsEmpty($conf)) {
      if ($this->required) {
        return new BrokenConfigurator($this, get_defined_vars(), "Required, but empty.");
      }

      return NULL;
    }

    return $this->nonEmptyConfGetValue($conf);
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  abstract protected function nonEmptyConfGetValue($conf);

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   *   An emptyness object, or
   *   NULL, if the configurator is in fact required and thus no valid conf
   *   counts as empty.
   */
  public function getEmptyness() {
    return $this->required ? NULL : $this;
  }

  /**
   * Default behavior for confIsEmpty(). Override if necessary.
   *
   * @param mixed $conf
   *
   * @return bool
   *   TRUE, if $conf is both valid and empty.
   */
  public function confIsEmpty($conf) {
    return NULL === $conf || '' === $conf || [] === $conf;
  }

  /**
   * Gets a valid configuration where $this->confIsEmpty($conf) returns TRUE.
   *
   * @return mixed|null
   */
  public function getEmptyConf() {
    return NULL;
  }
}