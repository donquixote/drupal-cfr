<?php

namespace Drupal\cfrapi\Configurator\Group;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;

class Configurator_GroupSchema extends Configurator_GroupGrandBase {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $groupSchema;

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  private $configurators;

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function __construct(
    CfSchema_GroupInterface $groupSchema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    // Construct all configurators here, when throwing exceptions is still
    // allowed.
    $configurators = [];
    foreach ($groupSchema->getItemSchemas() as $k => $itemSchema) {
      $configurators[$k] = $schemaToConfigurator->schemaGetConfigurator($itemSchema);
    }

    $this->configurators = $configurators;
    $this->groupSchema = $groupSchema;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  protected function getConfigurators() {
    return $this->configurators;
  }

  /**
   * @return string[]
   */
  protected function getLabels() {
    return $this->groupSchema->getLabels();
  }
}
