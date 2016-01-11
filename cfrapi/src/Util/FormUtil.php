<?php

namespace Drupal\cfrapi\Util;

final class FormUtil extends UtilBase {

  /**
   * @param array $element
   */
  static function onProcessBuildDependency(array &$element) {
    /* @see _cfrapi_process_element_dependency() */
    $element['#process'][] = '_cfrapi_process_element_dependency';
  }

  /**
   * Makes the second form element depend on the first, with AJAX.
   *
   * @param array $element
   * @param array $form
   * @param array $form_state
   */
  static function elementsBuildDependency(array &$element, array $form, array $form_state) {

    $keys = element_children($element);
    if (count($keys) < 2) {
      return;
    }
    list($dependedKey, $dependingKey) = element_children($element);
    $dependedElement =& $element[$dependedKey];
    $dependingElement =& $element[$dependingKey];

    if (!is_array($dependingElement)) {
      return;
    }

    if (!isset($form['form_build_id'])) {
      # dpm(ddebug_backtrace(TRUE));
    }

    if (!isset($element['#name'])) {
      # dpm(ddebug_backtrace(TRUE));
    }

    $form_build_id = $form['form_build_id']['#value'];
    $uniqid = sha1($form_build_id . serialize($element['#parents']));

    // See https://www.drupal.org/node/752056 "AJAX Forms in Drupal 7".
    $dependedElement['#ajax'] = array(
      /* @see _cfrapi_depended_element_ajax_callback() */
      'callback' => '_cfrapi_depended_element_ajax_callback',
      'wrapper' => $uniqid,
      'method' => 'replace',
    );

    $dependedElement['#depending_element_reference'] =& $dependingElement;

    // Special handling of ajax for views.
    /* @see views_ui_edit_form() */
    // See https://www.drupal.org/node/1183418
    if (1
      && isset($form_state['view'])
      && module_exists('views_ui')
      && $form_state['view'] instanceof \view
    ) {
      // @todo Does this always work?
      $dependedElement['#ajax']['path'] = $_GET['q'];
    }

    if (empty($dependingElement)) {
      $dependingElement += array(
        '#type' => 'themekit_container',
        '#markup' => '<!-- -->',
      );
    }

    $dependingElement['#prefix'] = '<div id="' . $uniqid . '">';
    $dependingElement['#suffix'] = '</div>';
    $dependingElement['#tree'] = TRUE;
  }

}
