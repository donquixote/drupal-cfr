<?php

namespace Drupal\cfrapi\PhpToPhp;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Util\UtilBase;

final class PhpToPhpUtil extends UtilBase {

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
