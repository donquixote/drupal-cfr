<?php

namespace Drupal\cfrapi\Configurator\Sequence;

use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\HtmlUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Sequence;
use Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @see \Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Sequence
 */
class Configurator_Sequence implements OptionalConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $configurator;

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $emptyness;

  /**
   * @var callable|null
   */
  private $itemLabelCallback;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   * @param \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface $emptyness
   *
   * @return \Drupal\cfrapi\Configurator\Sequence\Configurator_SequenceBase
   */
  public static function create(
    ConfiguratorInterface $configurator,
    ConfEmptynessInterface $emptyness
  ) {
    return new Configurator_Sequence2($configurator, $emptyness);
  }

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
   * @param callable|null $itemLabelCallback
   *
   * @return static
   */
  public function withItemLabelCallback($itemLabelCallback) {
    $clone = clone $this;
    $clone->itemLabelCallback = $itemLabelCallback;
    return $clone;
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
   * @return mixed[]
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf) {

    if (NULL === $conf) {
      return [];
    }
    if (!is_array($conf)) {
      throw new InvalidConfigurationException('Configuration must be an array or NULL.');
    }

    $values = [];
    foreach ($conf as $delta => $deltaConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        throw new InvalidConfigurationException("Deltas must be non-negative integers.");
      }
      if ($this->emptyness->confIsEmpty($deltaConf)) {
        // Skip empty values.
        continue;
      }
      $deltaValue = $this->configurator->confGetValue($deltaConf);
      $values[] = $deltaValue;
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

    if (NULL !== $label && '' !== $label && 0 !== $label) {
      $form = [
        '#type' => 'container',
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
      '#process' => [function (array $element /*, array &$form_state */) use ($obj, $conf) {
        return $obj->elementProcess($element, $conf);
      }],
      '#after_build' => [function (array $element, FormStateInterface $form_state) use ($obj) {
        return $obj->elementAfterBuild($element, $form_state);
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
      $element[$delta] = $this->configurator->confGetForm(
        $itemConf,
        $this->deltaGetItemLabel($delta));
    }

    // Element for new item.
    $element[] = $this->configurator->confGetForm(
      $this->emptyness->getEmptyConf(),
      $this->deltaGetItemLabel(NULL));
    // @todo AJAX button to add new item?
    // @todo Drag and drop to rearrange items.

    return $element;
  }

  /**
   * @param int|string|null $delta
   *
   * @return string
   */
  private function deltaGetItemLabel($delta) {

    if (NULL !== $this->itemLabelCallback) {
      return call_user_func($this->itemLabelCallback, $delta);
    }

    return (NULL === $delta)
      ? t('New item')
      : t('Item !n', ['!n' => '#' . HtmlUtil::sanitize($delta)]);
  }

  /**
   * Callback for '#after_build' to clean up empty items in the form value.
   *
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  private function elementAfterBuild(array $element, FormStateInterface $form_state) {

    $value = ConfUtil::confExtractNestedValue(
      $form_state->getValues(),
      $element['#parents']);

    if (!is_array($value)) {
      $value = [];
    }

    foreach ($value as $delta => $itemInput) {
      if ($this->emptyness->confIsEmpty($itemInput)) {
        unset($value[$delta]);
      }
    }

    $value = array_values($value);

    ConfUtil::confSetNestedValue(
      $form_state->getValues(),
      $element['#parents'],
      $value);

    if (isset($element['#title']) && '' !== $element['#title']) {
      $element['#theme_wrappers'][] = 'form_element';
    }

    return $element;
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

    if (NULL === $conf || [] === $conf) {
      return '[]';
    }

    if (!is_array($conf)) {
      return $helper->incompatibleConfiguration($conf, "Configuration must be an array or NULL.");
    }

    $phpStatements = array();
    foreach ($conf as $delta => $deltaConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return $helper->incompatibleConfiguration($conf, "Sequence array keys must be non-negative integers.");
      }
      if ($this->emptyness->confIsEmpty($deltaConf)) {
        // Skip empty values.
        continue;
      }
      $phpStatements[] = $this->configurator->confGetPhp($deltaConf, $helper);
    }

    $phpParts = [];
    foreach (array_values($phpStatements) as $delta => $deltaPhp) {
      $phpParts[] = ''
        # . "\n"
        . "\n// Sequence item #$delta"
        . "\n  $deltaPhp,";
    }

    $php = implode("\n", $phpParts);

    return "[$php\n]";
  }
}
