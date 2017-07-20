<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\Exception\CfSchemaCreationException;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;

class CfSchema_Drilldown_FromDefinitionMap extends CfSchema_Drilldown_BufferedBase {

  /**
   * @var \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

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
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   * @param \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    DefinitionMapInterface $definitionMap,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel,
    DefinitionToSchemaInterface $definitionToSchema,
    CfContextInterface $context = NULL
  ) {
    $this->definitionMap = $definitionMap;
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
    foreach ($this->definitionMap->getDefinitionsById() as $id => $definition) {
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
    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return NULL;
    }
    return $this->definitionToLabel->definitionGetLabel($definition, $id);
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  protected function idDetermineIfKnown($id) {
    return NULL !== $this->definitionMap->idGetDefinition($id);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  protected function idBuildSchema($id) {

    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return NULL;
    }

    try {
      return $this->definitionToSchema->definitionGetSchema($definition, $this->context);
    }
    catch (CfSchemaCreationException $e) {
      dpm($definition, $e->getMessage());
      // @todo Maybe report this somewhere?
      return NULL;
    }
  }
}
