<?php

namespace Donquixote\Cf\DefmapToDrilldownSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_FromDefinitionMap;

class DefmapToDrilldownSchema implements DefmapToDrilldownSchemaInterface {

  /**
   * @var \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @param \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   */
  public function __construct(
    DefinitionToSchemaInterface $definitionToSchema,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel
  ) {
    $this->definitionToSchema = $definitionToSchema;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
  }

  /**
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Context\CfContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function defmapGetDrilldownSchema(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL) {

    return new CfSchema_Drilldown_FromDefinitionMap(
      $definitionMap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToSchema,
      $context);
  }
}
