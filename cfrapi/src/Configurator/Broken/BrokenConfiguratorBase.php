<?php

namespace Drupal\cfrapi\Configurator\Broken;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueBase;
use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

abstract class BrokenConfiguratorBase extends BrokenValueBase implements BrokenConfiguratorInterface, ConfToPhpInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    // @todo Add an element that causes validation to fail.
    return [
      '#markup' => '- ' . t('Broken configurator') . ' -<pre>' . print_r($this, TRUE) . '</pre>',
    ];
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return '- ' . t('Broken configurator') . ' -';
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {
    return new BrokenValue($this, get_defined_vars(), 'Broken configurator');
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  function confGetPhp($conf, CodegenHelperInterface $helper) {
    return $helper->brokenConfigurator($conf, '');
  }

}
