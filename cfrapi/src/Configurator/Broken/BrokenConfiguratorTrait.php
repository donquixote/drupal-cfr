<?php

namespace Drupal\cfrapi\Configurator\Broken;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Exception\ConfToValueException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

trait BrokenConfiguratorTrait {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm(
    /** @noinspection PhpUnusedParameterInspection */ $conf,
    /** @noinspection PhpUnusedParameterInspection */ $label
  ) {
    // @todo Add an element that causes validation to fail.
    return [
      '#markup' => '- ' . t('Broken configurator') . ' -<pre>' . print_r($this, TRUE) . '</pre>',
      '#element_validate' => [
        function (array $element) {
          form_error($element, "Broken configurator. The form will always fail to validate.");
        }
      ],
    ];
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary(
    /** @noinspection PhpUnusedParameterInspection */ $conf,
    /** @noinspection PhpUnusedParameterInspection */ SummaryBuilderInterface $summaryBuilder
  ) {
    return '- ' . t('Broken configurator') . ' -';
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue(/** @noinspection PhpUnusedParameterInspection */ $conf) {
    throw new ConfToValueException("Broken configurator");
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    return $helper->incompatibleConfiguration($conf, 'Broken configurator');
  }

}
