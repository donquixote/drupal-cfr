<?php

namespace Donquixote\Cf\ConfToPhp\Helper;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Exception\EvaluatorException_UnsupportedSchema;
use Donquixote\Cf\Util\PhpUtil;

abstract class PhpHelperBase implements PhpHelperInterface {

  /**
   * @return string
   */
  public function unsupportedSchema() {
    return PhpUtil::exception(
      EvaluatorException_UnsupportedSchema::class,
      "Unsupported schema.");
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function incompatibleConfiguration($conf, $message) {
    return PhpUtil::exception(
      EvaluatorException_IncompatibleConfiguration::class,
      "Incomptible configuration");
  }

  /**
   * @param string $message
   *
   * @return string
   */
  public function invalidConfiguration($message) {
    return PhpUtil::exception(
      EvaluatorException_IncompatibleConfiguration::class,
      "Invalid configuration");
  }
}
