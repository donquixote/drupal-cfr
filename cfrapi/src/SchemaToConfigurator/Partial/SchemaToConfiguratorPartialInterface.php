<?php

namespace Drupal\cfrapi\SchemaToConfigurator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;

interface SchemaToConfiguratorPartialInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetConfigurator(
    CfSchemaInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetOptionalConfigurator(
    CfSchemaInterface $schema,
    SchemaToConfiguratorInterface $schemaToConfigurator);

}
