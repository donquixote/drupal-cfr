<?php

namespace Drupal\cfrfamily\DefmapToDrilldownSchema;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface;
use Drupal\cfrfamily\DrilldownSchema\CfSchema_Drilldown_FromDefinitionMap;

class DefmapToDrilldownSchema implements DefmapToDrilldownSchemaInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface
   */
  private $definitionToCfrSchema;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @param \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface $definitionToCfrSchema
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   */
  public function __construct(
    DefinitionToCfrSchemaInterface $definitionToCfrSchema,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel
  ) {
    $this->definitionToCfrSchema = $definitionToCfrSchema;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
  }

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function defmapGetDrilldownSchema(DefinitionMapInterface $definitionMap, CfrContextInterface $context = NULL) {

    return new CfSchema_Drilldown_FromDefinitionMap(
      $definitionMap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToCfrSchema,
      $context);
  }
}
