<?php
use Drupal\cfrapi\Context\CfrContext;

/**
 * Implements hook_element_info().
 */
function cfrplugin_element_info() {

  return [
    'cfrplugin' => [
      '#input' => TRUE,
      /* @see _cfrplugin_element_process() */
      '#process' => ['_cfrplugin_element_process'],
      /* @see _cfrplugin_element_value() */
      '#value_callback' => '_cfrplugin_element_value',
      '#cfrplugin_interface' => NULL,
      '#cfrplugin_context' => NULL,
    ],
  ];
}

/**
 * @param array $element
 * @param array $form_state
 * @param array $form
 *
 * @return array
 */
function _cfrplugin_element_process(array $element, array &$form_state, array &$form) {

  $cfrContext = $element['#cfrplugin_context'];
  if (is_array($cfrContext)) {
    $cfrContext = new CfrContext($cfrContext);
  }

  $element['cfrplugin'] = cfrplugin()
    ->interfaceGetConfigurator($element['#cfrplugin_interface'], $cfrContext)
    ->confGetForm($element['#default_value'], $element['#title']);

  $element['cfrplugin']['#parents'] = $element['#parents'];

  return $element;
}

/**
 * @param array $element
 * @param mixed|false $input
 * @param array $form_state
 *
 * @return mixed;
 */
function _cfrplugin_element_value(array $element, $input = FALSE, array $form_state = []) {

  if (FALSE !== $input) {
    return $input;
  }

  if (isset($element['#default_value'])) {
    return $element['#default_value'];
  }

  return [];
}
