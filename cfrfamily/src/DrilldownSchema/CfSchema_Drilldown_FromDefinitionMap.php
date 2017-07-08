<?php

namespace Drupal\cfrfamily\DrilldownSchema;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_PassthruTrait;
use Donquixote\Cf\Schema\CfSchema_Drilldown_BufferedBase;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\SchemaCreationException;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;

class CfSchema_Drilldown_FromDefinitionMap extends CfSchema_Drilldown_BufferedBase {

  use CfSchema_Drilldown_PassthruTrait;

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGroupLabel;

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface
   */
  private $definitionToCfrSchema;

  /**
   * @var \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  private $context;

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface $definitionToCfrSchema
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   */
  public function __construct(
    DefinitionMapInterface $definitionMap,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel,
    DefinitionToCfrSchemaInterface $definitionToCfrSchema,
    CfrContextInterface $context = NULL
  ) {
    $this->definitionMap = $definitionMap;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGroupLabel = $definitionToGroupLabel;
    $this->definitionToCfrSchema = $definitionToCfrSchema;
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
  protected function idBuildCfrSchema($id) {

    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return NULL;
    }

    try {
      return $this->definitionToCfrSchema->definitionGetCfrSchema($definition, $this->context);
    }
    catch (SchemaCreationException $e) {
      // @todo Maybe report this somewhere?
      return NULL;
    }
  }
}
