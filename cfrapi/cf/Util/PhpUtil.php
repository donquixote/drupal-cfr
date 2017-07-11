<?php

namespace Donquixote\Cf\Util;

class PhpUtil extends UtilBase {

  /**
   * @param string $exceptionClass
   * @param string $message
   *
   * @return string
   */
  public static function exception($exceptionClass, $message) {

    $messagePhp = var_export($message, TRUE);

    return <<<EOT
// @todo Fix the generated code manually.
call_user_func(
  function(){
    throw new \\$exceptionClass($messagePhp)
  });
EOT;
  }

}
