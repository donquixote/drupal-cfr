<?php

namespace Drupal\cfrapi\FormOptionalifier;

interface FormOptionalifierInterface {

  /**
   * @param array $form
   *
   * @return array
   */
  public function formGetOptional(array $form);

}
