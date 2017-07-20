<?php

namespace Donquixote\Cf\Schema\OptionsVal;

use Donquixote\Cf\Schema\IdVal\CfSchema_IdValInterface;

/**
 * @todo This is redundant, people should only use IdVal or ValueToValue instead.
 */
interface CfSchema_OptionsValInterface extends CfSchema_IdValInterface {

  /**
   * Same as parent, but a more specific return type.
   *
   * @return \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  public function getDecorated();

}
