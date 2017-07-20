<?php

namespace Donquixote\Cf\Schema\Id;

class CfSchema_Id_DefinitionsKey implements CfSchema_IdInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var string
   */
  private $key;

  /**
   * @param array[] $definitions
   * @param string $key
   */
  public function __construct(array $definitions, $key) {
    $this->definitions = $definitions;
    $this->key = $key;
  }

  /**
   * @param string|mixed $id
   *
   * @return bool
   */
  public function idIsKnown($id) {

    return !empty($this->definitions[$id][$this->key]);
  }
}
