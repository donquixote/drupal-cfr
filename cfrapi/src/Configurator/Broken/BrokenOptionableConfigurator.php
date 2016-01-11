<?php

namespace Drupal\cfrapi\Configurator\Broken;

use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Enum;
use /** @noinspection PhpDeprecationInspection */
  Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface;

/** @noinspection PhpDeprecationInspection
 * @deprecated
 */
class BrokenOptionableConfigurator extends BrokenConfiguratorBase implements OptionableConfiguratorInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetOptionalForm($conf, $label) {
    return array(
      '#markup' => '- ' . t('Broken configurator') . ' -<pre>' . print_r($this, TRUE) . '</pre>',
    );
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness() {
    return new ConfEmptyness_Enum();
  }

  /**
   * @return string
   */
  function getEmptySummary() {
    return t('None');
  }

  /**
   * @return mixed
   */
  function getEmptyValue() {
    return NULL;
  }
}
