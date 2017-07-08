<?php

namespace Drupal\cfrplugin\Util;

use Drupal\cfrapi\Util\UtilBase;

final class UiUtil extends UtilBase {

  /**
   * @param string $interface
   *
   * @return bool
   */
  public static function interfaceNameIsValid($interface) {
    $fragment = DRUPAL_PHP_FUNCTION_PATTERN;
    $backslash = preg_quote('\\', '/');
    $regex = '/^' . $fragment . '(' . $backslash . $fragment . ')*$/';
    return 1 === preg_match($regex, $interface);
  }

}
