<?php

namespace Drupal\cfrapi\Configurator\Sequence;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Sequence;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhp_NotSupported;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
use Drupal\cfrapi\ConfToPhp\ConfToPhpUtil;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

/**
 * @see \Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Sequence
 */
class Configurator_Sequence implements OptionalConfiguratorInterface, ConfToPhpInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $configurator;

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface $configurator
   */
  public function __construct(OptionalConfiguratorInterface $configurator) {
    $this->configurator = $configurator;
    if (NULL === $this->emptyness = $configurator->getEmptyness()) {
      throw new \InvalidArgumentException("The provided configurator has no valid values that count as empty.");
    }
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  public function getEmptyness() {
    return new ConfEmptyness_Sequence($this->emptyness);
  }

  /**
   * Builds the argument value to use at the position represented by this
   * handler.
   *
   * @param mixed $conf
   *   Setting value from configuration.
   *
   * @return mixed[]|\Drupal\cfrapi\BrokenValue\BrokenValueInterface
   */
  public function confGetValue($conf) {
    if (NULL === $conf) {
      return [];
    }
    if (!is_array($conf)) {
      return new BrokenValue($this, get_defined_vars(), 'Configuration must be an array or NULL.');
    }
    $values = [];
    foreach ($conf as $delta => $deltaConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return new BrokenValue($this, get_defined_vars(), "Deltas must be non-negative integers.");
      }
      if ($this->emptyness->confIsEmpty($deltaConf)) {
        // Skip empty values.
        continue;
      }
      $deltaValue = $this->configurator->confGetValue($deltaConf);
      $values[] = $deltaValue;
      if ($deltaValue instanceof BrokenValueInterface) {
        return new BrokenValue($this, get_defined_vars(), 'One of the values is broken.');
      }
      // @todo Really? Why do the values need to be objects?
      if (!is_object($deltaValue)) {
        # \Drupal\krumong\dpm(get_defined_vars(), __METHOD__);
        break;
      }
    }
    return $values;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    if (!is_array($conf)) {
      $conf = [];
    }
    return $summaryBuilder->buildSequence($this->configurator, $conf);
  }

  /**
   * @param mixed $conf
   *   Setting value from configuration.
   * @param null|string $label
   *
   * @return array
   *   A form element(s) array.
   */
  public function confGetForm($conf, $label) {

    if (!is_array($conf)) {
      $conf = [];
    }

    $obj = $this;

    if (NULL !== $label && '' !== $label && 1) {
      $form = [
        '#type' => 'fieldset',
        '#title' => $label,
      ];
    }
    else {
      $form = [
        '#type' => 'container',
      ];
    }

    $form['#attributes']['class'][] = 'cfrapi-child-options';

    return $form + [
      '#input' => TRUE,
      '#default_value' => $conf,
      '#process' => [function (array $element, array &$form_state) use ($obj, $conf) {
        return $obj->elementProcess($element, $conf);
      }],
      '#after_build' => [function (array $element, array &$form_state) use ($obj, $conf) {
        return $obj->elementAfterBuild($element, $form_state, $conf);
      }],
    ];
  }

  /**
   * @param array $element
   * @param array $conf
   *
   * @return array
   */
  private function elementProcess(array $element, array $conf) {

    $value = $element['#value'];
    if (!is_array($value)) {
      $value = [];
    }

    foreach ($value as $delta => $itemValue) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Skip non-numeric and negative keys.
        continue;
      }

      if ($this->emptyness->confIsEmpty($itemValue)) {
        // Skip empty items.
        continue;
      }

      $itemConf = isset($conf[$delta]) ? $conf[$delta] : NULL;
      $element[$delta] = $this->configurator->confGetForm($itemConf, t('Item !n', ['!n' => '#' . check_plain($delta)]));
    }

    // Element for new item.
    $element[] = $this->configurator->confGetForm($this->emptyness->getEmptyConf(), t('New item'));
    // @todo AJAX button to add new item?
    // @todo Drag and drop to rearrange items.

    return $element;
  }

  /**
   * Callback for '#after_build' to clean up empty items in the form value.
   *
   * @param array $element
   * @param array $form_state
   * @param array $conf
   *
   * @return array
   */
  private function elementAfterBuild(array $element, array &$form_state, array $conf) {

    $value = drupal_array_get_nested_value($form_state['values'], $element['#parents']);
    if (!is_array($value)) {
      $value = [];
    }

    foreach ($value as $delta => $itemInput) {
      if ($this->emptyness->confIsEmpty($itemInput)) {
        unset($value[$delta]);
      }
    }

    drupal_array_set_nested_value($form_state['values'], $element['#parents'], $value);

    return $element;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CodegenHelperInterface $helper) {

    if (NULL === $conf || [] === $conf) {
      return '[]';
    }

    if (!is_array($conf)) {
      return $helper->incompatibleConfiguration($conf, "Configuration must be an array or NULL.");
    }

    $confToPhp = !$this->configurator instanceof ConfToPhpInterface
      ? new ConfToPhp_NotSupported($this->configurator)
      : $this->configurator;

    $phpStatements = array();
    foreach ($conf as $delta => $deltaConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return new BrokenValue($this, get_defined_vars(), "Deltas must be non-negative integers.");
      }
      if ($this->emptyness->confIsEmpty($deltaConf)) {
        // Skip empty values.
        continue;
      }
      $phpStatements[] = $confToPhp->confGetPhp($deltaConf, $helper);
    }


    $php = '';
    foreach (array_values($phpStatements) as $delta => $deltaPhp) {
      $php .= "\n// Item #$delta"
        . "\n  " . $deltaPhp . ',';
    }

    return "[$php\n]";
  }
}
