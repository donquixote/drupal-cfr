<?php

namespace Donquixote\Cf\ConfToValue\Helper;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Exception\EvaluatorException_UnsupportedSchema;

abstract class ConfToValueHelperBase implements ConfToValueHelperInterface {

  /**
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  protected function unsupportedSchema() {
    throw new EvaluatorException_UnsupportedSchema("Unknown schema.");
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function incompatibleConfiguration($conf, $message) {
    throw new EvaluatorException_IncompatibleConfiguration($message);
  }

  /**
   * @param string $message
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function invalidConfiguration($message) {
    throw new EvaluatorException_IncompatibleConfiguration($message);
  }
}
