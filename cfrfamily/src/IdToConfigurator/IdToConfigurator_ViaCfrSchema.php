<?php

namespace Drupal\cfrfamily\IdToConfigurator;

use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrapi\Exception\SchemaCreationException;
use Drupal\cfrfamily\IdToCfrSchema\IdToCfrSchemaInterface;

class IdToConfigurator_ViaCfrSchema implements IdToConfiguratorInterface {

  /**
   * @var \Drupal\cfrfamily\IdToCfrSchema\IdToCfrSchemaInterface
   */
  private $idToCfrSchema;

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   */
  private $schemaToConfigurator;

  /**
   * @param \Drupal\cfrfamily\IdToCfrSchema\IdToCfrSchemaInterface $idToCfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $schemaToConfigurator
   */
  public function __construct(
    IdToCfrSchemaInterface $idToCfrSchema,
    CfrSchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    $this->idToCfrSchema = $idToCfrSchema;
    $this->schemaToConfigurator = $schemaToConfigurator;
  }

  /**
   * @param string|int $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  public function idGetConfigurator($id) {

    if (NULL === $schema = $this->idToCfrSchema->idGetCfrSchema($id)) {
      return NULL;
    }

    try {
      return $this->schemaToConfigurator->cfrSchemaGetConfigurator($schema);
    }
    catch (SchemaCreationException $e) {
      return NULL;
    }
  }
}
