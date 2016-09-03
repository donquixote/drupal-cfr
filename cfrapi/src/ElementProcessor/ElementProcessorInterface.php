<?php

namespace Drupal\cfrapi\ElementProcessor;

interface ElementProcessorInterface {

  /**
   * @param array $element
   * @param array $form_state
   *
   * @return array
   */
  public function __invoke(array $element, array &$form_state);

}
