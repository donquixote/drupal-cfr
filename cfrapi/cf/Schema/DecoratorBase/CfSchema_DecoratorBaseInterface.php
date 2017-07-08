<?php

namespace Donquixote\Cf\Schema\DecoratorBase;

interface CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getDecorated();

}
