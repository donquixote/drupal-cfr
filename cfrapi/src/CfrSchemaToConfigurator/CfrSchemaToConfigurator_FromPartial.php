<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\Partial\CfrSchemaToConfiguratorPartialInterface;

class CfrSchemaToConfigurator_FromPartial implements CfrSchemaToConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\Partial\CfrSchemaToConfiguratorPartialInterface
   */
  private $partial;

  /**
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\Partial\CfrSchemaToConfiguratorPartialInterface $partial
   */
  public function __construct(CfrSchemaToConfiguratorPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function cfrSchemaGetConfigurator(CfrSchemaInterface $cfrSchema) {

    if (FALSE === $configurator = $this->partial->cfrSchemaGetConfigurator($cfrSchema, $this)) {
      throw new \Exception("Unsupported schema.");
    }

    return $configurator;
  }
}
