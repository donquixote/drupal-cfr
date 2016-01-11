<?php

namespace Drupal\cfrfamily\DefinitionToLabel;

interface DefinitionToLabelInterface {

  /**
   * @param array $definition
   * @param string|null $else
   *
   * @return string
   */
  function definitionGetLabel(array $definition, $else);

}
