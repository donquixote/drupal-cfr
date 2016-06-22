<?php

namespace Drupal\cfrapi\GroupConfToPhpStatements;

use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;
use Drupal\cfrapi\Util\UtilBase;

class GroupConfToPhpStatementsUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param mixed $conf
   *
   * @return string[]
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public static function objGroupConfGetPhpStatements($object, $conf) {
    if (!$object instanceof GroupConfToPhpStatementsInterface) {
      $class = get_class($object);
      throw new PhpGenerationNotSupportedException("Object of class '$class' does not support code generation.");
    }
    return $object->confGetPhpStatements($conf);
  }

}
