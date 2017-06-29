<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator\Partial;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;

class CfrSchemaToConfiguratorPartial_InterfaceMap implements CfrSchemaToConfiguratorPartialInterface {

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface[]
   *   Format: $[$interface] = $cfrSchemaToConfigurator
   */
  private $map;

  /**
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface[] $map
   *   Format: $[$interface] = $cfrSchemaToConfigurator
   */
  public function __construct(array $map) {
    $this->map = $map;
  }

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   */
  public function cfrSchemaGetConfigurator(
    CfrSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator)
  {
    foreach ($this->map as $interface => $mapped) {
      if ($cfrSchema instanceof $interface) {
        if (FALSE !== $configurator = $mapped->cfrSchemaGetConfigurator($cfrSchema, $cfrSchemaToConfigurator)) {
          return $configurator;
        }
      }
    }

    return FALSE;
  }
}