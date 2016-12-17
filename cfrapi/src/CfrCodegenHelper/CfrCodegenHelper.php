<?php

namespace Drupal\cfrapi\CfrCodegenHelper;

use Donquixote\CallbackReflection\CodegenHelper\CodegenHelperBase;
use Drupal\cfrapi\Util\CodegenFailureUtil;

class CfrCodegenHelper extends CodegenHelperBase implements CfrCodegenHelperInterface {

  /**
   * @var array
   */
  private $problems = [];

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function recursionDetected($conf, $message) {

    $this->problems[] = t('Recursion detected.');

    /* @see CodegenFailureUtil::recursionDetected() */
    return CodegenFailureUtil::class . "::recursionDetected("
      . "\n  " . $this->export($conf) . ','
      . "\n  " . var_export($message, TRUE) . ')';
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function incompatibleConfiguration($conf, $message) {

    $this->problems[] = t('Incompatible configuration.');

    /* @see CodegenFailureUtil::incompatibleConfiguration() */
    return CodegenFailureUtil::class . "::incompatibleConfiguration("
      . "\n  " . $this->export($conf) . ','
      . "\n  " . var_export($message, TRUE) . ')';
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function brokenConfigurator($conf, $message = '') {

    $this->problems[] = t('Broken configurator.');

    /* @see CodegenFailureUtil::brokenConfigurator() */
    return CodegenFailureUtil::class . "::brokenConfigurator("
      . "\n  " . $this->export($conf) . ','
      . "\n  " . var_export($message, TRUE) . ')';
  }

  /**
   * @param object $object
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function notSupported($object, $conf, $message) {

    $this->problems[] = t('PHP generation not supported.');

    /* @see CodegenFailureUtil::notSupported() */
    return CodegenFailureUtil::class . "::notSupported("
      . "\n  " . get_class($object) . '::class,'
      . "\n  " . $this->export($conf) . ','
      . "\n  " . var_export($message, TRUE) . ')';
  }

  /**
   * @param string $message
   */
  protected function addProblem($message) {
    $this->problems[] = t($message);
  }
}
