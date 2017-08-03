<?php

namespace Drupal\cfrapi\Element;

use Drupal\Core\Render\Element\FormElement;

/**
 * @todo Not used currently.
 *
 * @FormElement("cfrapi_drilldown")
 */
class FormElement_Drilldown extends FormElement {

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
      /* @see _cfrapi_id_conf_element_process() */
      '#process' => ['_cfrapi_id_conf_element_process'],
      '#theme_wrappers' => ['themekit_container'],
      '#cfrapi_confToForm' => NULL,
    ];
  }
}
