<?php

namespace Drupal\cfrapi\Configurator\Optionable;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use /** @noinspection PhpDeprecationInspection */ Drupal\cfrapi\ConfToForm\OptionableConfToFormInterface;

/** @noinspection PhpDeprecationInspection
 *
 * @deprecated
 */
interface OptionableConfiguratorInterface extends ConfiguratorInterface, OptionableConfToFormInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetOptionalForm($conf, $label);

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness();

  /**
   * @return string
   */
  function getEmptySummary();

  /**
   * @return mixed
   */
  function getEmptyValue();

}
