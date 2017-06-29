<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface CfrSchemaToConfiguratorInterface {

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function cfrSchemaGetConfigurator(CfrSchemaInterface $cfrSchema);

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function cfrSchemaGetOptionalConfigurator(CfrSchemaInterface $cfrSchema);

}
