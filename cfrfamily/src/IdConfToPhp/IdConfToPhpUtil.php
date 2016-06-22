<?php

namespace Drupal\cfrfamily\IdConfToPhp;

use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;
use Drupal\cfrapi\Util\UtilBase;

final class IdConfToPhpUtil extends UtilBase {

  /**
   * @param mixed $object
   * @param string|int $id
   * @param mixed $conf
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  public static function objIdConfGetPhp($object, $id, $conf) {
    if (!$object instanceof IdConfToPhpInterface) {
      $class = get_class($object);
      throw new PhpGenerationNotSupportedException("\$this of class '$class' does not support code generation.");
    }
    return $object->idConfGetPhp($id, $conf);
  }

}
