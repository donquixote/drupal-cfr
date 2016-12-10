<?php

namespace Drupal\cfrapi\CodegenHelper;

interface CodegenHelperInterface {

  /**
   * Replacement of var_export()
   *
   * @param mixed $value
   *
   * @return string
   */
  public function export($value);

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function recursionDetected($conf, $message);

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function incompatibleConfiguration($conf, $message);

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function brokenConfigurator($conf, $message);

  /**
   * @param object $object
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function notSupported($object, $conf, $message);

}
