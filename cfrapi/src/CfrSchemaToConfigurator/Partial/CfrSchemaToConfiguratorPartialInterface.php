<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator\Partial;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;

interface CfrSchemaToConfiguratorPartialInterface {

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|false
   */
  public function cfrSchemaGetConfigurator(
    CfrSchemaInterface $cfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator);

}
