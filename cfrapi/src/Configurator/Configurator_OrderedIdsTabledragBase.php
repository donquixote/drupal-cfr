<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Array;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\Exception\ConfToValueException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

abstract class Configurator_OrderedIdsTabledragBase implements OptionalConfiguratorInterface {

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  public function getEmptyness() {
    return new ConfEmptyness_Array();
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

    $form['#attributes']['class'][] = 'cfrapi-sequence';

    $form += [
      '#input' => TRUE,
      '#default_value' => $conf,
      '#process' => [function (array $element, array &$form_state, array $form) use ($obj, $conf) {
        return $obj->elementProcess($element, $conf, $form_state, $form);
      }],
      /* @see _cfrapi_generic_value_callback() */
      '#value_callback' => '_cfrapi_generic_value_callback',
      '#cfrapi_value_callback' => function(array $element, $input = false, array &$form_state) use ($obj) {
        return $obj->elementValue($element, $input, $form_state);
      },
    ];

    $form['items'] = [];

    return $form;
  }

  /**
   * @param array $element
   * @param array|mixed|false $input
   * @param array $form_state
   *
   * @return array|bool|mixed
   */
  private function elementValue(array $element, $input = false, array &$form_state) {

    if (false === $input) {
      return $element['#default_value'] ?? [];
    }

    /** @noinspection CallableParameterUseCaseInTypeContextInspection */
    if (!is_array($input)) {
      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      return [];
    }

    $options = $this->getOptions();

    $values = [];
    foreach ($input as $delta => $value) {
      if (!\is_string($value) && !\is_int($value)) {
        continue;
      }
      if ('stop_here' === $delta) {
        break;
      }
      if (!isset($options[$value])) {
        continue;
      }
      $values[$value] = $value;
    }

    drupal_array_set_nested_value($form_state['input'], $element['#parents'], $values);
    drupal_array_set_nested_value($form_state['values'], $element['#parents'], $values);

    return $values;
  }

  /**
   * @param array $element
   * @param array $conf
   * @param array $form_state
   * @param array $form
   *
   * @return array
   */
  private function elementProcess(array $element, array $conf, array $form_state, array $form) {

    $values = $element['#value'];
    if (!is_array($values)) {
      $values = [];
    }

    $itemElements = [];
    foreach ($this->getOptions() as $v => $label) {
      $itemElement = [];
      $itemElement['value'] = [
        '#input' => false,
        '#type' => 'hidden',
        '#value' => $v,
        '#parents' => array_merge($element['#parents'], ['']),
        '#name' => $element['#name'] . '[]',
      ];
      $itemElement['label'] = [
        '#markup' => check_plain($label),
      ];
      $itemElements[$v] = $itemElement;
    }

    $element['items'] = [];

    // Add enabled items.
    foreach ($values as $v) {
      if (null === ($itemElement = $itemElements[$v] ?? null)) {
        continue;
      }
      $element['items'][] = $itemElement;
      unset($itemElements[$v]);
    }

    // Add a stopper element.
    $stopperElement = [];
    $stopperElement['value'] = [
      '#input' => false,
      '#type' => 'hidden',
      '#value' => '_',
      '#parents' => array_merge($element['#parents'], ['stop_here']),
      '#name' => $element['#name'] . '[stop_here]',
    ];
    $stopperElement['label'] = [
      '#markup' => '<strong>' . check_plain(t('Items below are disabled.')) . '</strong>',
    ];
    $element['items'][] = $stopperElement;

    // Add remaining items.
    foreach ($itemElements as $itemElement) {
      $element['items'][] = $itemElement;
    }

    /** @see _cfrapi_generic_pre_render() */
    $element['items']['#pre_render'][] = '_cfrapi_generic_pre_render';
    $element['items']['#cfrapi_pre_render'][] = function(array $itemsElement) {
      return $this->preRenderItems($itemsElement);
    };

    $element['#attached']['css'][] = drupal_get_path('module', 'cfrapi') . '/css/cfrapi.tabledrag.css';
    $element['#attached']['library'][] = ['system', 'jquery.cookie'];
    $element['#attached']['js'][] = 'misc/tabledrag.js';
    $element['#attached']['js'][] = drupal_get_path('module', 'cfrapi') . '/js/cfrapi.tabledrag.js';

    return $element;
  }

  /**
   * @param array $itemsElement
   *
   * @return array
   */
  private function preRenderItems(array $itemsElement) {

    $rows = [];
    foreach (element_children($itemsElement) as $delta) {
      if (!is_numeric($delta)) {
        continue;
      }
      $itemElement = $itemsElement[$delta];
      $cells = [];
      $cells[] = drupal_render($itemElement);

      $row = ['data' => $cells];
      if (isset($itemElement['value']) || true) {
        $row['class'][] = 'draggable';
      }

      $rows[] = $row;
      unset($itemsElement[$delta]);
    }

    if ([] === $rows) {
      return $itemsElement;
    }

    $tableElement = [
      /* @see theme_table() */
      '#theme' => 'table',
      '#rows' => $rows,
      '#attributes' => $itemsElement['#attributes'] ?? [],
    ];

    $tableElement['#attributes']['class'][] = 'cfrapi-tabledrag';

    $itemsElement['table'] = $tableElement;

    return $itemsElement;
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

    if (!\is_array($conf)) {
      if (null !== $conf) {
        return t('Incompatible configuration');
      }
      $conf = [];
    }

    foreach ($conf as $k => $value) {
      if (!\is_string($value) && !\is_int($value)) {
        return t('Incompatible configuration');
      }
    }

    $options = $this->getOptions();

    $values = array_combine($conf, $conf);

    $labels = array_intersect_key($options, $values);

    return implode(', ', $labels);
  }

  /**
   * @return string
   */
  protected function getEmptySummary() {
    return '- ' . t('None') . ' -';
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
  public function confGetValue($conf) {
    return $this->confDoGetValue($conf);
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

    try {
      $value = $this->confDoGetValue($conf);
    }
    catch (ConfToValueException $e) {
      return $helper->incompatibleConfiguration($conf, $e->getMessage());
    }

    return var_export($value, true);
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
  private function confDoGetValue($conf) {

    if (!\is_array($conf)) {
      if (null !== $conf) {
        throw new ConfToValueException("Unexpected value found.");
      }
      $conf = [];
    }

    foreach ($conf as $k => $value) {
      if (!\is_string($value) && !\is_int($value)) {
        throw new ConfToValueException("Unexpected value found.");
      }
    }

    $options = $this->getOptions();

    $values = array_combine($conf, $conf);

    return array_intersect_key($values, $options);
  }

  /**
   * @return string[]
   *   Format: $[$key] = $label
   */
  abstract protected function getOptions(): array;
}
