<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;

interface CfrSchemaToConfiguratorPartialInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetConfigurator(
    CfSchemaInterface $schema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator);

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface|false
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetOptionalConfigurator(
    CfSchemaInterface $schema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator);

}
