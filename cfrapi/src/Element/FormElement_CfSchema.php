<?php

namespace Drupal\cfrapi\Element;

use Donquixote\Cf\Form\D7\Util\D7FormSTAUtil;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrplugin\Hub\CfrPluginHub;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;

/**
 * @FormElement("cfrapi_cf_schema")
 */
class FormElement_CfSchema extends FormElement {

  /**
   * Returns the element properties for this element.
   *
   * @return array
   *   An array of element properties. See
   *   \Drupal\Core\Render\ElementInfoManagerInterface::getInfo() for
   *   documentation of the standard properties of all elements, and the
   *   return value format.
   */
  public function getInfo() {
    return [
      '#input' => TRUE,
      '#tree' => TRUE,
      '#process' => [
        /* @see process() */
        [self::class, 'process'],
      ],
      // This needs to be set.
      '#cf_schema' => NULL,
      '#title' => NULL,
      '#attached' => [
        'library' => [
          'cfrapi/form',
        ],
      ],
    ];
  }

  /**
   * @param array $element
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @param array $complete_form
   *
   * @return array
   */
  public static function process(
    array &$element,
    /** @noinspection PhpUnusedParameterInspection */ FormStateInterface $form_state,
    /** @noinspection PhpUnusedParameterInspection */ array &$complete_form
  ) {

    if (!isset($element['#cf_schema'])) {
      return $element;
    }

    $schema = $element['#cf_schema'];

    if (!$schema instanceof CfSchemaInterface) {
      return $element;
    }

    $container = CfrPluginHub::getContainer();

    $formator = D7FormSTAUtil::formator(
      $schema,
      $container->schemaToAnything
    );

    if (NULL === $formator) {
      return $element;
    }

    $element['schema'] = $formator->confGetD7Form(
      $element['#value'],
      $element['#title']);

    $element['schema']['#parents'] = $element['#parents'];

    return $element;
  }

  /**
   * @param array $element
   * @param mixed $input
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return mixed
   */
  public static function valueCallback(
    &$element,
    $input,
    FormStateInterface $form_state
  ) {

    # dpm(get_defined_vars(), __FUNCTION__ . '() - get_defined_vars()');

    if (FALSE === $input) {

      # dpm('Use $element[#default_value].');

      return isset($element['#default_value'])
        ? $element['#default_value']
        : [];
    }

    return $input;
  }
}
