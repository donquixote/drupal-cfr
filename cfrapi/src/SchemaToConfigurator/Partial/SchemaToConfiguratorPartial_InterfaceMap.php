<?php

namespace Drupal\cfrapi\SchemaToConfigurator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;

class SchemaToConfiguratorPartial_InterfaceMap implements SchemaToConfiguratorPartialInterface {

  /**
   * @var \Drupal\cfrapi\SchemaToConfigurator\Partial\SchemaToConfiguratorPartialInterface[]
   *   Format: $[$interface] = $schemaToConfigurator
   */
  private $map;

  /**
   * @param \Drupal\cfrapi\SchemaToConfigurator\Partial\SchemaToConfiguratorPartialInterface[] $map
   *   Format: $[$interface] = $schemaToConfigurator
   */
  public function __construct(array $map) {
    $this->map = $map;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   */
  public function schemaGetConfigurator(
    CfSchemaInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator)
  {
    foreach ($this->map as $interface => $mapped) {
      if ($schema instanceof $interface) {
        if (FALSE !== $configurator = $mapped->schemaGetConfigurator(
          $schema,
          $schemaToConfigurator)
        ) {
          return $configurator;
        }
      }
    }

    return FALSE;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|false
   */
  public function schemaGetOptionalConfigurator(
    CfSchemaInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    return FALSE;
  }
}
