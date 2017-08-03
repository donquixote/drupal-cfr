<?php

namespace Donquixote\Cf\Schema\Neutral;

/**
 * A "proxy" schema can be created before the decorated schema exists.
 *
 * This allows e.g. recursive schemas.
 */
abstract class CfSchema_Neutral_ProxyBase implements CfSchema_NeutralInterface {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private $decorated;

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \RuntimeException
   */
  public function getDecorated() {
    return NULL !== $this->decorated
      ? $this->decorated
      : $this->decorated = $this->doGetDecorated();
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  abstract protected function doGetDecorated();
}
