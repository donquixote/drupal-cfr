<?php

namespace Drupal\cfrapi\Configurator\Composite;

use /** @noinspection PhpDeprecationInspection */
  Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface;
use /** @noinspection PhpDeprecationInspection */
  Drupal\cfrapi\ConfToForm\OptionableConfToFormInterface;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;

/** @noinspection PhpDeprecationInspection
 * @deprecated
 */
class Configurator_OptionableComposite extends Configurator_Composite implements OptionableConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\ConfToForm\OptionableConfToFormInterface
   */
  private $confToForm;

  /**
   * @param \Drupal\cfrapi\ConfToForm\OptionableConfToFormInterface $confToForm
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param \Drupal\cfrapi\ConfToValue\ConfToValueInterface $confToValue
   */
  function __construct(
    /** @noinspection PhpDeprecationInspection */
    OptionableConfToFormInterface $confToForm,
    ConfToSummaryInterface $confToSummary,
    ConfToValueInterface $confToValue
  ) {
    $this->confToForm = $confToForm;
    parent::__construct($confToForm, $confToSummary, $confToValue);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetOptionalForm($conf, $label) {
    return $this->confToForm->confGetOptionalForm($conf, $label);
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness() {
    return $this->confToForm->getEmptyness();
  }

  /**
   * @return string
   */
  function getEmptySummary() {
    return '';
  }

  /**
   * @return mixed
   */
  function getEmptyValue() {
    return null;
  }
}
