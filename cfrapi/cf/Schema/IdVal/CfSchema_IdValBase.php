<?php

namespace Donquixote\Cf\Schema\IdVal;

use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_IdValBase extends CfSchema_DecoratorBase implements CfSchema_IdValInterface {

  /**
   * Same as parent, but requires an id schema.
   *
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $decorated
   */
  public function __construct(CfSchema_IdInterface $decorated) {
    parent::__construct($decorated);
  }

}
