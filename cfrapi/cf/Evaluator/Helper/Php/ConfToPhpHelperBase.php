<?php

namespace Donquixote\Cf\Evaluator\Helper\Php;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\PhpUtil;

abstract class ConfToPhpHelperBase implements ConfToPhpHelperInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string|null $message
   *
   * @return string
   */
  public function unsupportedSchema(CfSchemaInterface $schema, $message = NULL) {
    return PhpUtil::unsupportedSchema(
      $schema,
      $message);
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return string
   */
  public function incompatibleConfiguration($conf, $message) {
    return PhpUtil::incompatibleConfiguration($message);
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
