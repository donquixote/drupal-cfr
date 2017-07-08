<?php

namespace Donquixote\Cf\Schema\Iface;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;

interface CfSchema_IfaceInterface extends CfSchemaLocalInterface {

  /**
   * @return string
   */
  public function getInterface();

  /**
   * @return \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  public function getContext();

}
