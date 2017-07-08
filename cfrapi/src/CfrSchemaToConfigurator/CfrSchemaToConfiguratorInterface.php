<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator;

use Donquixote\Cf\Schema\CfSchemaInterface;

interface CfrSchemaToConfiguratorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetConfigurator(CfSchemaInterface $schema);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetOptionalConfigurator(CfSchemaInterface $schema);

}
