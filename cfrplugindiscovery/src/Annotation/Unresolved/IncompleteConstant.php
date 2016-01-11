<?php

namespace Drupal\cfrplugindiscovery\Annotation\Unresolved;

use vektah\parser_combinator\language\php\annotation\ConstLookup;

class IncompleteConstant implements UnresolvedArgumentInterface {

  /**
   * @var \vektah\parser_combinator\language\php\annotation\ConstLookup
   */
  private $arg;

  /**
   * @param \vektah\parser_combinator\language\php\annotation\ConstLookup $arg
   */
  function __construct(ConstLookup $arg) {
    $this->arg = $arg;
  }

}
