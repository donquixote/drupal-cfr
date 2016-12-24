<?php

namespace Drupal\cfrapi\CfrCodegenHelper;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelperInterface;

interface CfrCodegenHelperInterface extends CodegenHelperInterface {

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
   * @param object $object
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function notSupported($object, $conf, $message);

}
