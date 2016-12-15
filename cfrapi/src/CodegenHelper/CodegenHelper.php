<?php

namespace Drupal\cfrapi\CodegenHelper;

use Drupal\cfrapi\Util\CodegenFailureUtil;

class CodegenHelper implements CodegenHelperInterface {

  /**
   * @var array
   */
  private $problems = [];

  /**
   * Replacement of var_export()
   *
   * @param mixed $value
   *
   * @return string
   */
  public function export($value) {

    if (is_object($value)) {
      return $this->exportObject($value);
    }

    if (is_array($value)) {
      return $this->exportArray($value);
    }

    return var_export($value, TRUE);
  }

  /**
   * @param array $array
   *
   * @return string
   */
  private function exportArray(array $array) {

    if ([] === $array) {
      return '[]';
    }

    $pieces = [];
    if ($array === array_values($array)) {
      foreach ($array as $v) {
        $pieces[] = $this->export($v);
      }
    }
    else {
      foreach ($array as $k => $v) {
        $pieces[] = var_export($k, TRUE) . ' => ' . $this->export($v);
      }
    }

    $php_oneline = implode(', ', $pieces);
    if (FALSE === strpos($php_oneline, "\n") && strlen($php_oneline) < 30) {
      return '[' . $php_oneline . ']';
    }

    return "[\n  " . implode(",\n  ", $pieces) . "\n]";
  }

  /**
   * @param object $object
   *
   * @return string
   */
  private function exportObject($object) {

    if ($object instanceof \Closure) {
      return $this->exportClosure($object);
    }

    $this->problems[] = t('Exporting objects is not supported.');

    /* @see CodegenFailureUtil::cannotExportObject() */
    return CodegenFailureUtil::class . "::cannotExportObject("
      . "\n  " . get_class($object) . '::class)';
  }

  /**
   * @param \Closure $closure
   *
   * @return string
   */
  private function exportClosure(\Closure $closure) {

    $rf = new \ReflectionFunction($closure);
    $file = basename($rf->getFileName());
    $start_line = $rf->getStartLine();
    $end_line = $rf->getEndLine();

    $this->problems[] = t('Exporting closures is not supported.');

    $message = "See lines $start_line..$end_line of file \"$file\".";

    /* @see CodegenFailureUtil::cannotExportClosure() */
    return CodegenFailureUtil::class . "::cannotExportClosure("
      . "\n  " . var_export($message, TRUE) . ')';
  }

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
}
