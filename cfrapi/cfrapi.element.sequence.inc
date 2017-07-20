<?php

use Donquixote\Cf\Util\ConfUtil;
use Drupal\cfrpage\FormUtil;

/**
 * @param array $element
 * @param bool $input
 * @param array $form_state
 *
 * @return array|mixed
 */
function _cfrapi_sequence_element_value(array $element, $input = FALSE, array &$form_state) {

  dpm(get_defined_vars(), __FUNCTION__ . '() - get_defined_vars()');

  if (FALSE === $input) {

    dpm('Use $element[#default_value].');

    return isset($element['#default_value'])
      ? $element['#default_value']
      : [];
  }

  if (FALSE && isset($form_state['values'])) {

    $form_state_values = ConfUtil::confExtractNestedValue(
      $form_state['values'],
      $element['#parents']);

    if (NULL !== $form_state_values) {
      // This case occurs if this function is called on the second time during a
      // request, which is if someone used the "Remove item" or "Add more".
      # dpm($form_state_values, '$form_state_values');
      return $form_state_values;
    }
  }

  dpm($input, '$values from $input');

  return $input;
}

/**
 * @param array $element
 * @param array $form_state
 *
 * @return array
 */
function _cfrapi_sequence_element_process(array $element, array &$form_state) {

  dpm(__FUNCTION__);

  # dpm(get_defined_vars(), __FUNCTION__);

  if (NULL === $element['#sequence_item_element_callback']) {
    // @todo Log the error?
    # dpm($element, "Missing sequence item element callback");
    return $element;
  }

  $itemCallback = $element['#sequence_item_element_callback'];

  if (!is_callable($itemCallback)) {
    # dpm($itemCallback, 'Sequence item callback is not callable.');
    return $element;
  }

  // @todo Or use the #default_value?
  $values = $element['#value'];

  if (!is_array($values)) {
    $values = [];
  }

  $sequenceId = 'seq-' . md5($element['#name']);
  $sequenceItemsId = $sequenceId . '-items';

  $elementParents = $element['#parents'];

  $element['items'] = [
    '#type' => 'container',
    '#attributes' => ['id' => $sequenceItemsId],
    '#parents' => $elementParents,
  ];

  $elementParents = $element['#parents'];

  foreach ($values as $delta => $value) {

    $itemId = $sequenceId . '-item-' . $delta;

    $itemValueElement = call_user_func(
      $itemCallback,
      $value,
      'Item #' . check_plain($delta));

    $itemValueElement['#parents']
      = $itemParents
      = array_merge($elementParents, [$delta]);

    $itemRemoveButton = [
      '#type' => 'button',
      '#name' => implode('-', $itemParents) . '-remove',
      '#value' => t('Remove'),
      '#limit_validation_errors' => [$itemParents],
      # '#input' => FALSE,
      '#submit' => [
        function (array $form, array &$form_state) use ($itemParents) {
          dpm('remove submit');
          ConfUtil::confUnsetNestedValue($form_state['values'], $itemParents);
          ConfUtil::confUnsetNestedValue($form_state['input'], $itemParents);
          $form_state['rebuild'] = TRUE;
        }
      ],
      '#ajax' => [
        /* @see _cfrapi_sequence_element_ajax() */
        'callback' => function() use ($itemId) {
          return [
            '#type' => 'ajax',
            '#commands' => [
              ajax_command_remove('#' . $itemId),
            ],
          ];
        },
      ],
    ];

    $element['items'][$delta] = [
      '#type' => 'container',
      '#tree' => true,
      '#attributes' => ['id' => $itemId],
      'value' => $itemValueElement,
      'remove' => $itemRemoveButton,
    ];
  }

  $elementName = $element['#name'];

  $element['addmore'] = [
    '#parents' => array_merge($element['#parents'], ['addmore']),
    '#type' => 'submit',
    '#name' => $element['#name'] . '[addmore]',
    '#value' => t('Add item'),
    # '#sequence_element' => &$element,
    '#limit_validation_errors' => [],
    '#submit' => [
      function (array $form, array &$form_state) use ($elementParents) {
        $values = ConfUtil::confExtractNestedValue($form_state['values'], $elementParents);
        $values[] = NULL;
        end($values);
        $newDelta = key($values);
        $newItemParents = array_merge($elementParents, [$newDelta]);
        ConfUtil::confSetNestedValue($form_state['values'], $newItemParents, NULL);
        ConfUtil::confSetNestedValue($form_state['input'], $newItemParents, NULL);
        kdpm(get_defined_vars(), 'addmore submit');
        $form_state['rebuild'] = TRUE;
      }
    ],
    '#ajax' => [
      'callback' => function (array $form, array &$form_state) use ($sequenceId, $elementName) {

        kdpm($form_state['triggering_element'], __FUNCTION__);

        return $form_state['triggering_element']['#items_element_reference'];

        $commands = [];

        if (NULL === $element = FormUtil::elementFindDescendant(
          $form,
          ['#name' => $elementName])
        ) {
          $commands[] = ajax_command_alert('Parent element not found in ajax callback for sequence addmore button.');
        }
        else {
          $itemHtml = drupal_render($element);
          $commands[] = ajax_command_replace('#' . $sequenceId, $itemHtml, []);
        }

        kdpm(get_defined_vars(), __FUNCTION__);

        return [
          '#type' => 'ajax',
          '#commands' => $commands,
        ];
      },
      'wrapper' => $sequenceItemsId,
      'method' => 'replace',
    ],
  ];

  $element['#theme_wrappers'][] = 'container';
  $element['#attributes']['id'] = $sequenceId;
  /* @see _cfrapi_sequence_element_pre_render() */
  $element['#pre_render'][] = '_cfrapi_sequence_element_pre_render';

  $element['#post_render'][] = function($markup, array &$element) {
    # dpm($element, 'POST RENDER');
    return $markup;
  };

  $element['addmore']['#items_element_reference'] = &$element['items'];

  $element['#after_build_'][] = function (array &$element, array &$form_state) {
    return $element;
  };

  /*
  // Only integer keys!
  $values = array_values($values);

  foreach ($values as $delta => $value) {
    # $uq = uniqid('', TRUE);

    $item_element = call_user_func(
      $item_callback,
      $value,
      // Label
      'Item #' . $delta);

    $element['items'][$delta] = [
      'value' => $item_element,
      'remove' => [
        '#type' => 'button',
        '#value' => t('Remove item'),
      ],
    ];
  }
  */

  # dpm(get_defined_vars(), __FUNCTION__ . ' DONE');

  return $element;
}

/**
 * @param array $element
 *
 * @return array
 */
function _cfrapi_sequence_element_pre_render(array $element) {

  return $element;

  $element['table'] = [
    /* @see theme_themekit_table() */
    '#theme' => 'themekit_table',
    'items' => $element['items'],
    'addmore' => $element['addmore'],
  ];

  unset($element['items']);
  unset($element['addmore']);

  $element['table']['items']['#themekit_table_type'] = 'tbody';
  $element['table']['addmore']['#themekit_table_type'] = 'td';

  return $element;
}

/**
 * This is a trick to suppress form validation and submission.
 *
 * @param array $form
 * @param array $form_state
 *
 * @return array
 */
function _cfrapi_form_after_build_unset_process_input(array $form, array &$form_state) {
  # dpm(get_defined_vars(), __FUNCTION__);
  $form_state['process_input'] = FALSE;
  return $form;
}

/**
 * @param array $form
 * @param array $form_state
 *
 * @return array
 */
function _cfrapi_sequence_element_ajax(array $form, array $form_state) {

  return drupal_array_get_nested_value(
    $form,
    $form_state['triggering_element']['#parents_replace']);
}
