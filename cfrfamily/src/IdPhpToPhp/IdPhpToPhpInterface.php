<?php

namespace Drupal\cfrfamily\IdPhpToPhp;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;

/**
 * @see \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface::idValueGetValue()
 */
interface IdPhpToPhpInterface {

  /**
   * @param string $id
   * @param string $php
   *   PHP code to generate a value.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP code to generate a value.
   */
  public function idPhpGetPhp($id, $php, CodegenHelperInterface $helper);

}
