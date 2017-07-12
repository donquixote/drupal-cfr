<?php

namespace Donquixote\Cf\Schema\Iface;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;

interface CfSchema_IfaceWithContextInterface extends CfSchemaLocalInterface {

  /**
   * @return string
   */
  public function getInterface();

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext();

  /**
   * @return string
   */
  public function getCacheId();

}
