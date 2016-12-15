<?php

namespace Drupal\cfrapi\PhpProvider;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;

interface PhpProviderInterface {

  /**
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(CodegenHelperInterface $helper);

}
