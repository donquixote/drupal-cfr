<?php

namespace Drupal\cfrapi\PhpToPhp;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Util\UtilBase;

final class PhpToPhpUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param string $php
   *   PHP code to generate a value.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   Modified PHP code to generate a value.
   */
  public static function objPhpGetPhp($object, $php, CodegenHelperInterface $helper) {

    if (!$object instanceof PhpToPhpInterface) {
      return $helper->notSupported($object, NULL, "Object does not implement PhpProviderInterface.");
    }

    return $object->phpGetPhp($php);
  }

  /**
   * @param string[] $phpStatementsSerial
   *
   * @return string
   */
  public static function phpStatementsGetArrayPhp(array $phpStatementsSerial) {

    $php = '';
    foreach ($phpStatementsSerial as $deltaPhp) {
      $php .= "\n  " . $deltaPhp . ',';
    }

    return '' !== $php
      ? "[$php\n]"
      : '[]';
  }

  /**
   * @param string[] $phpStatementsAssoc
   *
   * @return string
   */
  public static function phpStatementsGetAssocPhp(array $phpStatementsAssoc) {

    $php = '';
    foreach ($phpStatementsAssoc as $delta => $deltaPhp) {
      $php .= "\n  " . var_export($delta, TRUE) . ' => ' . $deltaPhp . ',';
    }

    return '' !== $php
      ? "[$php\n]"
      : '[]';
  }

}
