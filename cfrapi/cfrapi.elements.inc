<?php

use Donquixote\Cf\Util\ConfUtil;

/**
 * @param array $element
 * @param array $form_state
 *
 * @return array
 */
function _cfrapi_sequence_element_process_(array $element, array &$form_state) {

  dpm(get_defined_vars(), __FUNCTION__);

  if (NULL === $element['#sequence_item_element_callback']) {
    // @todo Log the error?
    dpm($element, "Missing sequence item element callback");
    return $element;
  }

  $item_callback = $element['#sequence_item_element_callback'];

  if (!is_callable($item_callback)) {
    dpm($item_callback, 'Sequence item callback is not callable.');
    return $element;
  }

  // @todo Or use the #default_value?
  $values = $element['#value'];

  if (!is_array($values)) {
    $values = [];
  }

  $id = md5($element['#name']);

  $element['items'] = [
  ];

  foreach ($values as $delta => $value) {
    $uq = uniqid('', TRUE);
    $item_element = call_user_func($item_callback, $value, 'Item #' . $uq);
    $element['items'][$uq] = [
      '#tree' => true,
      'value' => $item_element,
      'remove' => [
        '#type' => 'button',
        '#name' => $element['#name'] . '[items][' . $uq . '][remove]',
        '#value' => t('Remove item'),
        '#parents_replace' => $element['#parents'],
        '#ajax' => [
          /* @see _cfrapi_sequence_element_ajax() */
          'callback' => '_cfrapi_sequence_element_ajax',
          'wrapper' => $id,
          'method' => 'replace',
        ],
      ],
    ];
  }

  $element['addmore'] = [
    '#type' => 'button',
    '#name' => $element['#name'] . '[addmore]',
    '#value' => t('Add item'),
    '#parents_replace' => $element['#parents'],
    '#ajax' => [
      /* @see _cfrapi_sequence_element_ajax() */
      'callback' => '_cfrapi_sequence_element_ajax',
      'wrapper' => $id,
      'method' => 'replace',
    ],
  ];

  $element['#theme_wrappers'][] = 'container';
  $element['#attributes']['id'] = $id;
  /* @see _cfrapi_sequence_element_pre_render() */
  $element['#pre_render'][] = '_cfrapi_sequence_element_pre_render';

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

  return $element;
}

/**
 * @param array $element
 *
 * @return array
 */
function _cfrapi_sequence_element_pre_render_(array $element) {

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
 * @param array $element
 * @param bool $input
 * @param array $form_state
 *
 * @return array|mixed
 */
function _cfrapi_sequence_element_value_(array $element, $input = FALSE, array &$form_state) {

  dpm(get_defined_vars(), __FUNCTION__ . '() - get_defined_vars()');

  if (FALSE === $input) {

    dpm('Use $element[#default_value].');

    return isset($element['#default_value'])
      ? $element['#default_value']
      : [];
  }

  if (isset($form_state['values'])) {

    $form_state_values = ConfUtil::confExtractNestedValue(
      $form_state['values'],
      $element['#parents']);

    if (NULL !== $form_state_values) {
      // This case occurs if this function is called on the second time during a
      // request, which is if someone used the "Remove item" or "Add more".
      dpm($form_state_values, '$form_state_values');
      return $form_state_values;
    }
  }

  $id = md5($element['#name']);

  $addmore = FALSE;
  $delta_to_remove = NULL;
  if (isset($form_state['input']['_triggering_element_name'])) {
    $triggering_element_name = $form_state['input']['_triggering_element_name'];
    if (0 === strpos($triggering_element_name, $element['#name'])) {
      $triggering_element_name_suffix = substr($triggering_element_name, strlen($element['#name']));
      if ('[addmore]' === $triggering_element_name_suffix) {
        $addmore = TRUE;
      }
      elseif (preg_match('@^\[items\]\[(.+)\]\[remove\]$@', $triggering_element_name_suffix, $m)) {
        $delta_to_remove = $m[1];
      }
    }
  }

  $values = [];
  if (isset($input['items']) && is_array($input['items'])) {

    foreach ($input['items'] as $delta => $item_input) {
      if (isset($item_input['value'])) {
        if (!isset($item_input['remove']) && $delta !== $delta_to_remove) {
          $values[] = $item_input['value'];
        }
        else {
          dpm(get_defined_vars(), __FUNCTION__ . ' - "Remove item" button clicked.');
          /* @see _cfrapi_form_after_build_unset_process_input() */
          $form_state['complete form']['#after_build'][] = '_cfrapi_form_after_build_unset_process_input';
        }
      }
    }

    dpm($values, '$values from $input');
  }

  if (isset($input['addmore']) || TRUE === $addmore) {
    dpm(get_defined_vars(), __FUNCTION__ . ' - "Add item" button clicked.');
    /* @see _cfrapi_form_after_build_unset_process_input() */
    $form_state['complete form']['#after_build'][] = '_cfrapi_form_after_build_unset_process_input';
    // Add an "empty" item.
    $values[] = NULL;
  }

  dpm(get_defined_vars(), __FUNCTION__ . ' - DONE');

  return $values;
}

/**
 * @param array $element
 * @param array $form_state
 *
 * @return array
 */
function _cfrapi_sequence_element_after_build_(array $element, array &$form_state) {

  dpm(__FUNCTION__);

  // This needs to happen here, not in value callback.
  form_set_value($element, $element['#value'], $form_state);

  ConfUtil::confUnsetNestedValue(
    $form_state['input'],
    array_merge($element['#parents'], ['items']));

  # drupal_array_set_nested_value($form_state['input'], $element['#parents'], [], TRUE);

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
function _cfrapi_form_after_build_unset_process_input_(array $form, array &$form_state) {
  dpm(get_defined_vars(), __FUNCTION__);
  $form_state['process_input'] = FALSE;
  return $form;
}

/**
 * @param array $form
 * @param array $form_state
 *
 * @return array
 */
function _cfrapi_sequence_element_ajax_(array $form, array $form_state) {

  return drupal_array_get_nested_value(
    $form,
    $form_state['triggering_element']['#parents_replace']);
}
