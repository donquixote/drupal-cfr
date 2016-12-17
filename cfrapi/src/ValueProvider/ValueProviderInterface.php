<?php

namespace Drupal\cfrapi\ValueProvider;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

interface ValueProviderInterface {

  /**
   * @return mixed
   */
  public function getValue();

  /**
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(CfrCodegenHelperInterface $helper);
}
