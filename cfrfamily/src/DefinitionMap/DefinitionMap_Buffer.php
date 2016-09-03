<?php

namespace Drupal\cfrfamily\DefinitionMap;


use Drupal\cfrfamily\DefinitionsById\DefinitionsByIdInterface;

/**
 * Buffers the plugins for a specific
 */
class DefinitionMap_Buffer implements DefinitionMapInterface {

  /**
   * @var array[]|null
   */
  private $definitions;

  /**
   * @var \Drupal\cfrfamily\DefinitionsById\DefinitionsByIdInterface
   */
  private $decorated;

  /**
   * @param \Drupal\cfrfamily\DefinitionsById\DefinitionsByIdInterface $decorated
   */
  public function __construct(DefinitionsByIdInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $id
   *
   * @return array|null
   */
  public function idGetDefinition($id) {
    $definitions = $this->getDefinitionsById();
    return isset($definitions[$id])
      ? $definitions[$id]
      : NULL;
  }

  /**
   * @return array[]
   */
  public function getDefinitionsById() {
    return isset($this->definitions)
      ? $this->definitions
      : $this->definitions = $this->decorated->getDefinitionsById();
  }
}
