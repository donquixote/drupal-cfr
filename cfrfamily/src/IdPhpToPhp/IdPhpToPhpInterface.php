<?php

namespace Drupal\cfrfamily\IdPhpToPhp;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

/**
 * @see \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface::idValueGetValue()
 */
interface IdPhpToPhpInterface {

  /**
   * @param string $id
   * @param string $php
   *   PHP code to generate a value.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP code to generate a value.
   */
  public function idPhpGetPhp($id, $php, CfrCodegenHelperInterface $helper);

}
