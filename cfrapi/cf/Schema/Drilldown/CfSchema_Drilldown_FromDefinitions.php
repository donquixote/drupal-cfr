<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Exception\CfSchemaCreationException;

class CfSchema_Drilldown_FromDefinitions extends CfSchema_Drilldown_BufferedBase {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGroupLabel;

  /**
   * @var \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param array[] $definitions
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   * @param \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    array $definitions,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel,
    DefinitionToSchemaInterface $definitionToSchema,
    CfContextInterface $context = NULL
  ) {
    $this->definitions = $definitions;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGroupLabel = $definitionToGroupLabel;
    $this->definitionToSchema = $definitionToSchema;
    $this->context = $context;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  protected function buildGroupedOptions() {

    $options = ['' => []];
    foreach ($this->definitions as $id => $definition) {
      $label = $this->definitionToLabel->definitionGetLabel($definition, $id);
      $groupLabel = $this->definitionToGroupLabel->definitionGetLabel($definition, '');
      $options[$groupLabel][$id] = $label;
    }

    return $options;
  }

  /**
   * @param string|int $id
   *
   * @return null|string
   */
  protected function idBuildLabel($id) {

    if (!isset($this->definitions[$id])) {
      return NULL;
    }

    return $this->definitionToLabel->definitionGetLabel($this->definitions[$id], $id);
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  protected function idDetermineIfKnown($id) {
    return isset($this->definitions[$id]);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  protected function idBuildSchema($id) {

    if (!isset($this->definitions[$id])) {
      return NULL;
    }

    try {
      return $this->definitionToSchema->definitionGetSchema(
        $this->definitions[$id],
        $this->context);
    }
    catch (CfSchemaCreationException $e) {
      dpm($this->definitions[$id], $e->getMessage());
      // @todo Maybe report this somewhere?
      return NULL;
    }
  }
}
