<?php

namespace Drupal\cfrapi\SchemaToConfigurator;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\SchemaToConfigurator\Partial\SchemaToConfiguratorPartialInterface;
use Drupal\cfrapi\Exception\UnsupportedSchemaException;

class SchemaToConfigurator_FromPartial implements SchemaToConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\SchemaToConfigurator\Partial\SchemaToConfiguratorPartialInterface
   */
  private $partial;

  /**
   * @param \Drupal\cfrapi\SchemaToConfigurator\Partial\SchemaToConfiguratorPartialInterface $partial
   */
  public function __construct(SchemaToConfiguratorPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetConfigurator(CfSchemaInterface $schema) {

    if (FALSE === $configurator = $this->partial->schemaGetConfigurator($schema, $this)) {
      throw new UnsupportedSchemaException("Unsupported schema.");
    }

    return $configurator;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetOptionalConfigurator(CfSchemaInterface $schema) {

    if (FALSE === $configurator = $this->partial->schemaGetOptionalConfigurator(
      $schema,
      $this)
    ) {
      $schemaClass = get_class($schema);
      throw new UnsupportedSchemaException("Un-optionable schema of class $schemaClass.");
    }

    return $configurator;
  }
}
