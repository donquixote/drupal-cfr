<?php

namespace Donquixote\Cf\DefinitionMap;

class DefinitionMap implements DefinitionMapInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @param array $definitions
   */
  public function __construct(array $definitions) {
    $this->definitions = $definitions;
  }

  /**
   * @param string $id
   *
   * @return array|null
   */
  public function idGetDefinition($id) {

    return isset($this->definitions[$id])
      ? $this->definitions[$id]
      : NULL;
  }

  /**
   * @return array[]
   */
  public function getDefinitionsById() {

    return $this->definitions;
  }
}
