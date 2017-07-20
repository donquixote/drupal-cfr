<?php

namespace Drupal\cfrapi\SchemaToConfigurator;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface SchemaToConfiguratorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetConfigurator(CfSchemaInterface $schema);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetOptionalConfigurator(CfSchemaInterface $schema);

}
