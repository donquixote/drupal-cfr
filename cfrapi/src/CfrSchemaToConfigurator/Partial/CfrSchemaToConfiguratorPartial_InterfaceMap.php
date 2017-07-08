<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;

class CfrSchemaToConfiguratorPartial_InterfaceMap implements CfrSchemaToConfiguratorPartialInterface {

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\Partial\CfrSchemaToConfiguratorPartialInterface[]
   *   Format: $[$interface] = $cfrSchemaToConfigurator
   */
  private $map;

  /**
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\Partial\CfrSchemaToConfiguratorPartialInterface[] $map
   *   Format: $[$interface] = $cfrSchemaToConfigurator
   */
  public function __construct(array $map) {
    $this->map = $map;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   */
  public function cfrSchemaGetConfigurator(
    CfSchemaInterface $cfrSchema,
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

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|false
   */
  public function cfrSchemaGetOptionalConfigurator(
    CfSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    return FALSE;
  }
}
