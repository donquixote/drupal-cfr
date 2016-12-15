<?php

namespace Drupal\cfrapi\ConfToPhp;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;

/**
 * @see \Drupal\cfrapi\ConfToValue\ConfToValueInterface::confGetValue()
 */
interface ConfToPhpInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CodegenHelperInterface $helper);

}
