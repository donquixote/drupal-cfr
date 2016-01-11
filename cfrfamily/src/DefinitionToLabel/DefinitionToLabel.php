<?php

namespace Drupal\cfrfamily\DefinitionToLabel;



class DefinitionToLabel implements DefinitionToLabelInterface {

  /**
   * @var string
   */
  private $key;

  /**
   * @return \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel
   */
  static function create() {
    return new self('label');
  }

  /**
   * @return \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel
   */
  static function createGroupLabel() {
    return new self('group_label');
  }

  /**
   * @param string $key
   *   E.g. 'label'.
   */
  function __construct($key) {
    $this->key = $key;
  }

  /**
   * @param array $definition
   * @param string|null $else
   *
   * @return string
   */
  function definitionGetLabel(array $definition, $else) {
    return isset($definition[$this->key])
      ? $definition[$this->key]
      : $else;
  }
}
