<?php

namespace Drupal\cfrapi\Configurator\Sequence;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Sequence;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

/**
 * @see \Drupal\cfrapi\ConfEmptyness\SequenceEmptyness
 */
class Configurator_Sequence implements SequenceConfiguratorInterface, OptionalConfiguratorInterface {

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
    $form = [
      '#type' => 'fieldset',
      '#title' => $label,
    ];
    foreach ($conf as $delta => $itemConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Skip non-numeric and negative keys.
        continue;
      }
      if (!$this->emptyness->confIsEmpty($itemConf)) {
        $form[$delta] = $this->configurator->confGetForm($itemConf, t('Item !n', ['!n' => '#' . check_plain($delta)]));
      }
    }
    // Element for new item.
    $form[] = $this->configurator->confGetForm($this->emptyness->getEmptyConf(), t('New item'));
    // @todo AJAX button to add new item?
    // @todo Drag and drop to rearrange items.
    return $form;
  }
}
