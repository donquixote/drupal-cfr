<?php

namespace Drupal\cfrapi\PhpToPhp;

use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;
use Drupal\cfrapi\Util\UtilBase;

final class PhpToPhpUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param string $php
   *   PHP code to generate a value.
   *
   * @return string
   *   Modified PHP code to generate a value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  public static function objPhpGetPhp($object, $php) {
    if (!$object instanceof PhpToPhpInterface) {
      $class = get_class($object);
      throw new PhpGenerationNotSupportedException("Object of class '$class' does not support code generation.");
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
      ? 'array(' . $php . "\n" . ')'
      : 'array()';
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
      ? 'array(' . $php . "\n" . ')'
      : 'array()';
  }

}
