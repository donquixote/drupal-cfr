<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\CfrSchema\Group\GroupSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;

class Configurator_GroupSchema extends Configurator_GroupGrandBase {

  /**
   * @var \Drupal\cfrapi\CfrSchema\Group\GroupSchemaInterface
   */
  private $groupSchema;

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   */
  private $cfrSchemaToConfigurator;

  /**
   * @param \Drupal\cfrapi\CfrSchema\Group\GroupSchemaInterface $groupSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   */
  public function __construct(
    GroupSchemaInterface $groupSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    $this->groupSchema = $groupSchema;
    $this->cfrSchemaToConfigurator = $cfrSchemaToConfigurator;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  protected function getConfigurators() {

    // @todo Cache the configurators.

    $configurators = [];
    foreach ($this->groupSchema->getItemSchemas() as $key => $itemSchema) {
      $configurators[$key] = $this->cfrSchemaToConfigurator->cfrSchemaGetConfigurator($itemSchema);
    }

    return $configurators;
  }

  /**
   * @return string[]
   */
  protected function getLabels() {
    return $this->groupSchema->getLabels();
  }

  /**
   * @param mixed $conf
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf) {
    $values = parent::confGetValue($conf);
    return $this->groupSchema->valuesGetValue($values);
  }
}
