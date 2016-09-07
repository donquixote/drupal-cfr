<?php

namespace Drupal\cfrapi\ElementProcessor;

/**
 * To be used as a $form[*]['#process'][] callback.
 */
interface ElementProcessorInterface {

  /**
   * @param array $element
   * @param array $form_state
   *
   * @return array
   */
  public function __invoke(array $element, array &$form_state);

}
