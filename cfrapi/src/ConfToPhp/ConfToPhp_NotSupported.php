<?php

namespace Drupal\cfrapi\ConfToPhp;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;

class ConfToPhp_NotSupported implements ConfToPhpInterface {

  /**
   * @var object
   */
  private $object;

  /**
   * @param object $object
   */
  public function __construct($object) {
    $this->object = $object;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CodegenHelperInterface $helper) {
    return $helper->notSupported($this, $conf, "Not supported");
  }
}
