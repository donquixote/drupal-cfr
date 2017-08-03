<?php

namespace Drupal\cfrapi\ElementProcessor;

use Drupal\Core\Form\FormStateInterface;

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
  public function __invoke(array $element, FormStateInterface $form_state);

}
