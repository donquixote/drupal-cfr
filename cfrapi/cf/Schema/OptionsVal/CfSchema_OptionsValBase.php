<?php

namespace Donquixote\Cf\Schema\OptionsVal;

use Donquixote\Cf\Schema\IdVal\CfSchema_IdValBase;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

abstract class CfSchema_OptionsValBase extends CfSchema_IdValBase implements CfSchema_OptionsValInterface {

  /**
   * Same as parent, but requires an options schema.
   *
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $decorated
   */
  public function __construct(CfSchema_OptionsInterface $decorated) {
    parent::__construct($decorated);
  }

}
