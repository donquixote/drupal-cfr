<?php

namespace Donquixote\Cf\Form\D7\Util;

use Donquixote\Cf\Schema\Options\AbstractOptionsSchemaInterface;
use Donquixote\Cf\Util\UtilBase;

class D7FormUtil extends UtilBase {

  /**
   * Form element #process callback.
   *
   * Makes the second form element depend on the first, with AJAX.
   *
   * @param array $element
   * @param array $form_state
   * @param array $form
   *
   * @return array
   */
  public static function elementsBuildDependency(array $element, array &$form_state, array $form) {

    $keys = element_children($element);
    if (count($keys) < 2) {
      return $element;
    }
    list($dependedKey, $dependingKey) = element_children($element);
    $dependedElement =& $element[$dependedKey];
    $dependingElement =& $element[$dependingKey];

    if (!is_array($dependingElement)) {
      return $element;
    }

    $form_build_id = $form['form_build_id']['#value'];
    $uniqid = sha1($form_build_id . serialize($element['#parents']));

    // See https://www.drupal.org/node/752056 "AJAX Forms in Drupal 7".
    $dependedElement['#ajax'] = [
      /* @see _cfrapi_depended_element_ajax_callback() */
      'callback' => '_cfrapi_depended_element_ajax_callback',
      'wrapper' => $uniqid,
      'method' => 'replace',
    ];

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
      $dependedElement['#ajax']['path'] = empty($form_state['url'])
        ? url($_GET['q'], array('absolute' => TRUE))
        : $form_state['url'];

      # drupal_array_set_nested_value($form_state['values'], $element['#parents'], [], TRUE);
      # drupal_array_set_nested_value($form_state['input'], $element['#parents'], [], TRUE);
    }

    if (empty($dependingElement)) {
      $dependingElement += [
        '#type' => 'themekit_container',
        '#markup' => '<!-- -->',
      ];
    }

    $dependingElement['#prefix'] = '<div id="' . $uniqid . '" class="cfrapi-depending-element-container">';
    $dependingElement['#suffix'] = '</div>';
    $dependingElement['#tree'] = TRUE;

    return $element;
  }

  /**
   * @param \Donquixote\Cf\Schema\Options\AbstractOptionsSchemaInterface $schema
   * @param string|int $id
   * @param string $label
   * @param bool $required
   *
   * @return string[]|string[][]
   */
  public static function optionsSchemaBuildSelectElement(
    AbstractOptionsSchemaInterface $schema,
    $id,
    $label,
    $required = TRUE
  ) {
    $element = [
      '#title' => $label,
      '#type' => 'select',
      '#options' => self::optionsSchemaGetSelectOptions($schema),
      '#default_value' => $id,
    ];

    if (NULL !== $id && !self::idExistsInSelectOptions($id, $element['#options'])) {
      $element['#options'][$id] = t("Unknown id '@id'", ['@id' => $id]);
      $element['#element_validate'][] = function(array $element) use ($id) {
        if ((string)$id === (string)$element['#value']) {
          form_error($element, t("Unknown id %id. Maybe the id did exist in the past, but it currently does not.", ['%id' => $id]));
        }
      };
    }

    if ($required) {
      $element['#required'] = TRUE;
    }
    else {
      $element['#empty_value'] = '';
    }

    return $element;
  }

  /**
   * @param \Donquixote\Cf\Schema\Options\AbstractOptionsSchemaInterface $schema
   *
   * @return string[]|string[][]
   */
  public static function optionsSchemaGetSelectOptions(AbstractOptionsSchemaInterface $schema)
  {
    $options = $schema->getGroupedOptions();
    if (isset($options[''])) {
      $options = $options[''] + $options;
    }
    unset($options['']);

    return $options;
  }

  /**
   * @param string $id
   * @param array $options
   *
   * @return bool
   */
  private static function idExistsInSelectOptions($id, array $options) {

    if (isset($options[$id]) && !is_array($options[$id])) {
      return TRUE;
    }

    foreach ($options as $optgroup) {
      if (is_array($optgroup) && isset($optgroup[$id])) {
        return TRUE;
      }
    }

    return FALSE;
  }

}
