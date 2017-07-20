<?php

namespace Donquixote\Cf\SchemaBase\Decorator;

interface CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getDecorated();

}
