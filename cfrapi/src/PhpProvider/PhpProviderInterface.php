<?php

namespace Drupal\cfrapi\PhpProvider;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

interface PhpProviderInterface {

  /**
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(CfrCodegenHelperInterface $helper);

}
