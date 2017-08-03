<?php

namespace Donquixote\Cf\Schema\Neutral;

use Donquixote\Cf\Schema\CfSchemaInterface;

class CfSchema_Neutral_ProxyWithCallable extends CfSchema_Neutral_ProxyBase {

  /**
   * @var callable
   */
  private $schemaCallback;

  /**
   * @param callable $schemaCallback
   */
  public function __construct($schemaCallback) {
    $this->schemaCallback = $schemaCallback;
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \RuntimeException
   */
  public function doGetDecorated() {

    $schema = call_user_func($this->schemaCallback);

    if (!$schema instanceof CfSchemaInterface) {
      throw new \RuntimeException("Callback did not return a schema.");
    }

    return $schema;
  }
}
