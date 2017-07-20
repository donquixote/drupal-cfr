<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\Schema\Definition\CfSchema_Definition;

class CfSchema_Drilldown_DefmapSimple extends CfSchema_Drilldown_BufferedBase {

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
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    DefinitionMapInterface $definitionMap,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel,
    CfContextInterface $context = NULL
  ) {
    $this->definitionMap = $definitionMap;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGroupLabel = $definitionToGroupLabel;
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

    return new CfSchema_Definition($definition, $this->context);
  }
}
