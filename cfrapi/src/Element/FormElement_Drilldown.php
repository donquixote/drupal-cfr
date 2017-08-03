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
      '#input' => TRUE,
      '#tree' => TRUE,
      /* @see _cfrapi_cf_schema_element_process() */
      '#process' => ['_cfrapi_cf_schema_element_process'],
      /* @see _cfrapi_cf_schema_element_value() */
      '#value_callback' => '_cfrapi_cf_schema_element_value',
      // This needs to be set.
      '#cf_schema' => NULL,
      '#title' => NULL,
    ];
  }
}
