<?php

namespace Drupal\cfrfamily\IdToConfigurator;

use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;
use Drupal\cfrapi\Exception\SchemaCreationException;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;

class IdToConfigurator_ViaCfSchema implements IdToConfiguratorInterface {

  /**
   * @var \Donquixote\Cf\IdToSchema\IdToSchemaInterface
   */
  private $idToSchema;

  /**
   * @var \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface
   */
  private $schemaToConfigurator;

  /**
   * @param \Donquixote\Cf\IdToSchema\IdToSchemaInterface $idToSchema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   */
  public function __construct(
    IdToSchemaInterface $idToSchema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    $this->idToSchema = $idToSchema;
    $this->schemaToConfigurator = $schemaToConfigurator;
  }

  /**
   * @param string|int $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  public function idGetConfigurator($id) {

    if (NULL === $schema = $this->idToSchema->idGetSchema($id)) {
      return NULL;
    }

    try {
      return $this->schemaToConfigurator->schemaGetConfigurator($schema);
    }
    catch (SchemaCreationException $e) {
      dpm($schema, $e->getMessage());
      return NULL;
    }
  }
}
