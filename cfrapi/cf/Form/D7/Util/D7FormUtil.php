<?php

namespace Donquixote\Cf\Form\D7\Util;

use Donquixote\Cf\SchemaBase\Options\CfSchemaBase_AbstractOptionsInterface;
use Donquixote\Cf\Util\UtilBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class D7FormUtil extends UtilBase {

  /**
   * Form element #process callback.
   *
   * Makes the second form element depend on the first, with AJAX.
   *
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param array $form
   *
   * @return array
   */
  public static function elementsBuildDependency(
    array $element,
    /** @noinspection PhpUnusedParameterInspection */ FormStateInterface $form_state,
    /** @noinspection PhpUnusedParameterInspection */ array $form
  ) {

    $keys = Element::children($element);
    if (count($keys) < 2) {
      return $element;
    }
    list($dependedKey, $dependingKey) = Element::children($element);
    $dependedElement =& $element[$dependedKey];
    $dependingElement =& $element[$dependingKey];

    if (!is_array($dependingElement)) {
      return $element;
    }

    # $form_build_id = $form['form_build_id']['#value'];
    $uniqid = ''
      . implode( '--', $element['#parents'])
      # . '--' . $form_build_id
      . '--' . $form_state->getFormObject()->getFormId()
      . '';

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
    /*
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
    */

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
   * @param \Donquixote\Cf\SchemaBase\Options\CfSchemaBase_AbstractOptionsInterface $schema
   * @param string|int $id
   * @param string $label
   * @param bool $required
   *
   * @return string[]|string[][]
   */
  public static function optionsSchemaBuildSelectElement(
    CfSchemaBase_AbstractOptionsInterface $schema,
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
      $element['#element_validate'][] = function(array $element, FormStateInterface $form_state) use ($id) {
        if ((string)$id === (string)$element['#value']) {
          $form_state->setError(
            $element,
            t(
              "Unknown id %id. Maybe the id did exist in the past, but it currently does not.",
              ['%id' => $id]));
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
   * @param \Donquixote\Cf\SchemaBase\Options\CfSchemaBase_AbstractOptionsInterface $schema
   *
   * @return string[]|string[][]
   */
  public static function optionsSchemaGetSelectOptions(CfSchemaBase_AbstractOptionsInterface $schema)
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
