<?php

namespace Drupal\cfrplugin\Element;

use Drupal\cfrapi\Context\CfrContext;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;

/**
 * @FormElement("cfrplugin")
 */
class FormElement_CfrPlugin extends FormElement {

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
      '#cfrplugin_interface' => NULL,
      '#cfrplugin_context' => NULL,
      '#title' => NULL,
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

    $cfrContext = $element['#cfrplugin_context'];
    if (is_array($cfrContext)) {
      $cfrContext = new CfrContext($cfrContext);
    }

    $configurator = empty($element['#required'])
      ? cfrplugin()->interfaceGetOptionalConfigurator($element['#cfrplugin_interface'], $cfrContext)
      : cfrplugin()->interfaceGetConfigurator($element['#cfrplugin_interface'], $cfrContext);

    # kdpm($configurator, __FUNCTION__);

    $element['cfrplugin'] = $configurator->confGetForm($element['#default_value'], $element['#title']);

    $element['cfrplugin']['#parents'] = $element['#parents'];

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

    if (FALSE !== $input) {
      return $input;
    }

    if (isset($element['#default_value'])) {
      return $element['#default_value'];
    }

    return [];
  }


}
