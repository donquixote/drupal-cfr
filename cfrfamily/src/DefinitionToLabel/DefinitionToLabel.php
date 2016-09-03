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
  public static function create() {
    return new self('label');
  }

  /**
   * @return \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel
   */
  public static function createGroupLabel() {
    return new self('group_label');
  }

  /**
   * @param string $key
   *   E.g. 'label'.
   */
  public function __construct($key) {
    $this->key = $key;
  }

  /**
   * @param array $definition
   * @param string|null $else
   *
   * @return string
   */
  public function definitionGetLabel(array $definition, $else) {
    return isset($definition[$this->key])
      ? $definition[$this->key]
      : $else;
  }
}
