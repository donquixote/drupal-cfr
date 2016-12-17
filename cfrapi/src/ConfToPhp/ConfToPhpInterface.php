<?php

namespace Drupal\cfrapi\ConfToPhp;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

/**
 * @see \Drupal\cfrapi\ConfToValue\ConfToValueInterface::confGetValue()
 */
interface ConfToPhpInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper);

}
