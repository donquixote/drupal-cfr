<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

class SchemaReplacerPartial_IfaceDefinitions extends SchemaReplacerPartial_IfaceDefinitionsBase {

  /**
   * @var array[][]
   */
  private $definitionss;

  /**
   * @param array[][] $definitionss
   */
  public function __construct(array $definitionss) {
    $this->definitionss = $definitionss;
  }

  /**
   * @param string $type
   *
   * @return array[]
   */
  protected function typeGetDefinitions($type) {

    return isset($this->definitionss[$type])
      ? $this->definitionss[$type]
      : [];
  }
}
