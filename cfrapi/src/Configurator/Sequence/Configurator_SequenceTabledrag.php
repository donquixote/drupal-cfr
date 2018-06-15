<?php

namespace Drupal\cfrapi\Configurator\Sequence;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Array;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\Exception\ConfToValueException;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

/**
 * @see \Drupal\cfrapi\ConfEmptyness\ConfEmptyness_Array
 */
class Configurator_SequenceTabledrag implements OptionalConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $configurator;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   */
  public function __construct(ConfiguratorInterface $configurator) {
    $this->configurator = $configurator;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  public function getEmptyness() {
    return new ConfEmptyness_Array();
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
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {

    if (NULL === $conf) {
      return [];
    }
    if (!\is_array($conf)) {
      throw new ConfToValueException('Configuration must be an array or NULL.');
    }

    $values = [];
    foreach ($conf as $delta => $deltaConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        throw new ConfToValueException("Deltas must be non-negative integers.");
      }
      $deltaValue = $this->configurator->confGetValue($deltaConf);
      $values[] = $deltaValue;
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
    if (!\is_array($conf)) {
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

    if (!\is_array($conf)) {
      // Always start with one stub item.
      $conf = [NULL];
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
      '#after_build' => [function (array $element, array &$form_state) use ($obj) {
        return $obj->elementAfterBuild($element, $form_state);
      }],
      /* @see _cfrapi_generic_value_callback() */
      '#value_callback' => '_cfrapi_generic_value_callback',
      '#cfrapi_value_callback' => function(array $element, $input, array &$form_state) use ($obj) {
        return $obj->elementValue($element, $input, $form_state);
      },
    ];

    return $form;
  }

  /**
   * @param array $element
   * @param array|mixed|false $input
   *   Raw value from form submission, or FALSE to use #default_value.
   * @param array $form_state
   *
   * @return array|bool|mixed
   */
  private function elementValue(array $element, $input, array &$form_state) {

    if (false === $input) {
      return $element['#default_value'] ?? [];
    }

    if (!\is_array($input)) {
      // Always start with one item.
      $input = [NULL];
    }

    $element_name = $element['#name'];
    $triggeringElementName = $form_state['input']['_triggering_element_name'] ?? '';
    if (0 === strpos($triggeringElementName, $element_name)) {
      if ($element_name . '[add_more]' === $triggeringElementName) {
        # $input[] = null;
      }
      else {
        foreach ($input as $delta => $itemInput) {
          if ($element_name . '[delete][' . $delta . ']' === $triggeringElementName) {
            # unset($input[$delta]);
          }
        }
      }
    }
    unset($input['delete']);
    unset($input['add_more']);
    $input = array_values($input);
    drupal_array_set_nested_value($form_state['input'], $element['#parents'], $input);
    drupal_array_set_nested_value($form_state['values'], $element['#parents'], $input);
    return $input;
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

    $value = $element['#value'];
    if (!\is_array($value)) {
      $value = [];
    }

    $triggering_element_name = $form_state['input']['_triggering_element_name'] ?? null;

    $add_more_button_name = $element['#name'] . '[add_more]';

    if ($add_more_button_name === $triggering_element_name) {
      # $value[] = null;
    }

    $value = array_values($value);

    $form_build_id = $form['form_build_id']['#value'];
    $uniqid = sha1($form_build_id . serialize($element['#parents']));

    $module_path = drupal_get_path('module', 'cfrapi');

    $element['#attached']['css'][] = $module_path . '/css/cfrapi.tabledrag.css';
    $element['#attached']['css'][] = $module_path . '/css/cfrapi.sequence-tabledrag.css';
    $element['#attached']['library'][] = ['system', 'jquery.cookie'];
    $element['#attached']['js'][] = 'misc/tabledrag.js';
    $element['#attached']['js'][] = $module_path . '/js/cfrapi.tabledrag.js';

    $element['items'] = [];
    $element['items']['#parents'] = $element['#parents'];

    /** @see _cfrapi_generic_pre_render() */
    $element['items']['#pre_render'][] = '_cfrapi_generic_pre_render';
    $element['items']['#cfrapi_pre_render'][] = function(array $itemsElement) {
      return $this->preRenderItems($itemsElement);
    };

    $parents = $element['#parents'];

    foreach ($value as $delta => $itemValue) {

      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Skip non-numeric and negative keys.
        continue;
      }

      $itemConf = isset($conf[$delta]) ? $conf[$delta] : NULL;
      $element['items'][$delta] = [
        # '#type' => 'container',
        '#attributes' => ['class' => ['cfrapi-sequence-item']],
      ];

      # $rowLabel = t('Item !n', ['!n' => '#' . check_plain($delta)]);

      $element['items'][$delta]['handle'] = [
        '#markup' => '<!-- -->',
      ];

      $element['items'][$delta]['item'] = [
        '#theme_wrappers' => ['container'],
        '#attributes' => [
          'class' => ['cfrapi-sequence-item-form'],
          # 'style' => 'display: none;',
        ],
      ];

      if ('' !== $sequenceLabel = $element['#title'] ?? '') {
        $itemLabel = t(
          '@sequence: item !n',
          [
            '@sequence' => $sequenceLabel,
            '!n' => '#' . check_plain($delta),
          ]);
      }
      else {
        $itemLabel = t(
          'Item !n',
          ['!n' => '#' . check_plain($delta)]);
      }

      $itemForm = $this->configurator->confGetForm($itemConf, $itemLabel);

      $element['items'][$delta]['item']['form']['conf'] = $itemForm;

      $element['items'][$delta]['item']['form']['conf']['#parents'] = array_merge($element['#parents'], [$delta]);

      $element['items'][$delta]['delete'] = [
        # '#type' => 'container',
        '#attributes' => ['class' => ['cfrapi-sequence-delete-container']],
      ];

      $element['items'][$delta]['delete']['button'] = [
        '#type' => 'submit',
        '#parents' => array_merge($element['#parents'], ['delete', $delta]),
        '#name' => $element['#name'] . '[delete][' . $delta . ']',
        '#value' => t('Remove'),
        '#attributes' => ['class' => ['cfrapi-sequence-delete']],
        '#submit' => [
          function(array $element, array &$form_state) use ($parents, $delta) {
            $value = drupal_array_get_nested_value($form_state['input'], $parents);
            if (!\is_array($value)) {
              $value = [];
            }
            unset($value[$delta]);
            $value = array_values($value);
            drupal_array_set_nested_value($form_state['input'], $parents, $value);
            $form_state['rebuild'] = TRUE;
          },
        ],
        '#ajax_relative_parents' => ['..', '..', '..'],
        '#ajax' => [
          'callback' => [$this, 'ajaxCallback'],
          'wrapper' => $uniqid,
          'method' => 'replace',
        ],
        '#limit_validation_errors' => [],
      ];
    }

    if (empty($element['items'])) {
      $element['items']['#markup'] = '<!-- -->';
    }

    $element['items']['#prefix'] = '<div id="' . $uniqid . '" class="cfrapi-sequence-items">';
    $element['items']['#suffix'] = '</div>';

    $element['add_more'] = [
      '#type' => 'submit',
      '#name' => $add_more_button_name,
      '#value' => t('Add another item'),
      '#attributes' => ['class' => ['cfrapi-sequence-add-more']],
      '#submit' => [
        function(array $element, array &$form_state) use ($parents) {
          $value = drupal_array_get_nested_value($form_state['input'], $parents);
          if (!\is_array($value)) {
            $value = [];
          }
          $value[] = null;
          $value = array_values($value);
          drupal_array_set_nested_value($form_state['input'], $parents, $value);
          $form_state['rebuild'] = TRUE;
        },
      ],
      '#ajax_relative_parents' => ['..', 'items'],
      '#ajax' => [
        'callback' => [$this, 'ajaxCallback'],
        'wrapper' => $uniqid,
        'method' => 'replace',
      ],
      '#limit_validation_errors' => [],
    ];

    return $element;
  }

  /**
   * @param array $form
   * @param array $form_state
   *
   * @return array
   */
  public function ajaxCallback(array $form, array &$form_state) {
    $parents = $form_state['triggering_element']['#array_parents'];
    foreach ($form_state['triggering_element']['#ajax_relative_parents'] as $parent) {
      if ('..' === $parent) {
        array_pop($parents);
      }
      else {
        $parents[] = $parent;
      }
    }
    $element = drupal_array_get_nested_value($form, $parents);

    return $element;
  }

  /**
   * Callback for '#after_build' to clean up empty items in the form value.
   *
   * @param array $element
   * @param array $form_state
   *
   * @return array
   */
  private function elementAfterBuild(array $element, array &$form_state) {

    foreach (['values', 'input'] as $key) {
      $value = drupal_array_get_nested_value($form_state[$key], $element['#parents']);
      if (!\is_array($value)) {
        $value = [];
      }
      else {
        unset($value['delete']);
        unset($value['add_more']);
      }
      drupal_array_set_nested_value($form_state[$key], $element['#parents'], $value);
    }

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
      $item_element = $itemsElement[$delta];
      $cells = [];
      foreach (element_children($item_element) as $colname) {
        $cell_element = $item_element[$colname];
        $cell = ['data' => drupal_render($cell_element)];
        $cell += $cell_element['#attributes'] ?? [];
        $cells[] = $cell;
      }

      $row = ['data' => $cells];
      $row += $item_element['#attributes'] ?? [];
      $row['class'][] = 'draggable';
      $rows[] = $row;
      unset($itemsElement[$delta]);
    }

    if ([] === $rows) {
      return $itemsElement;
    }

    $table_element = [
      /* @see theme_table() */
      '#theme' => 'table',
      '#rows' => $rows,
      '#attributes' => $itemsElement['#attributes'],
    ];

    $table_element['#attributes']['class'][] = 'cfrapi-tabledrag';

    $itemsElement['table'] = $table_element;

    return $itemsElement;
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

    if (!\is_array($conf)) {
      return $helper->incompatibleConfiguration($conf, "Configuration must be an array or NULL.");
    }

    $phpStatements = array();
    foreach ($conf as $delta => $deltaConf) {
      if ((string)(int)$delta !== (string)$delta || $delta < 0) {
        // Fail on non-numeric and negative keys.
        return $helper->incompatibleConfiguration($conf, "Sequence array keys must be non-negative integers.");
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
