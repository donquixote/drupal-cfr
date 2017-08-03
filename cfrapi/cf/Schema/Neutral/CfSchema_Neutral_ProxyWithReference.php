<?php

namespace Donquixote\Cf\Schema\Neutral;

use Donquixote\Cf\Schema\CfSchemaInterface;

class CfSchema_Neutral_ProxyWithReference extends CfSchema_Neutral_ProxyBase {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  private $schemaRef;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface|null $schemaRef
   */
  public function __construct(CfSchemaInterface &$schemaRef = NULL) {
    $this->schemaRef =& $schemaRef;
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \RuntimeException
   */
  public function doGetDecorated() {

    if (!$this->schemaRef instanceof CfSchemaInterface) {
      throw new \RuntimeException("Schema reference is still empty.");
    }

    return $this->schemaRef;
  }
}
