<?php

namespace Drupal\cfrplugindiscovery\Annotation\Unresolved;

class UnresolvedConstant implements UnresolvedArgumentInterface {

  /**
   * @var
   */
  private $name;

  /**
   * @param $name
   */
  function __construct($name) {
    $this->name = $name;
  }

}
