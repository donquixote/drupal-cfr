<?php

namespace Drupal\cfrapi\CfrSchemaToConfigurator;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchemaToConfigurator\Partial\CfrSchemaToConfiguratorPartialInterface;
use Drupal\cfrapi\Exception\UnsupportedSchemaException;

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
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetConfigurator(CfrSchemaInterface $cfrSchema) {

    if (FALSE === $configurator = $this->partial->cfrSchemaGetConfigurator($cfrSchema, $this)) {
      throw new UnsupportedSchemaException("Unsupported schema.");
    }

    return $configurator;
  }

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $cfrSchema
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function cfrSchemaGetOptionalConfigurator(CfrSchemaInterface $cfrSchema) {

    if (FALSE === $configurator = $this->partial->cfrSchemaGetOptionalConfigurator(
      $cfrSchema,
      $this)
    ) {
      $schemaClass = get_class($cfrSchema);
      throw new UnsupportedSchemaException("Un-optionable schema of class $schemaClass.");
    }

    return $configurator;
  }
}
