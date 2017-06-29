<?php

namespace Drupal\cfrrealm\TypeToConfigurator;

use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrrealm\TypeToDrilldownSchema\TypeToDrilldownSchemaInterface;

class TypeToConfigurator_ViaDrilldownSchema implements TypeToConfiguratorInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToDrilldownSchema\TypeToDrilldownSchemaInterface
   */
  private $typeToDrilldownSchema;

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   */
  private $cfrSchemaToConfigurator;

  /**
   * @param \Drupal\cfrrealm\TypeToDrilldownSchema\TypeToDrilldownSchemaInterface $typeToDrilldownSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   */
  public function __construct(
    TypeToDrilldownSchemaInterface $typeToDrilldownSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    $this->typeToDrilldownSchema = $typeToDrilldownSchema;
    $this->cfrSchemaToConfigurator = $cfrSchemaToConfigurator;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function typeGetConfigurator($type, CfrContextInterface $context = NULL) {
    $schema = $this->typeToDrilldownSchema->typeGetDrilldownSchema($type, $context);
    return $this->cfrSchemaToConfigurator->cfrSchemaGetConfigurator($schema);
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function typeGetOptionalConfigurator($type, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    $schema = $this->typeToDrilldownSchema->typeGetDrilldownSchema($type, $context);
    return $this->cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator($schema);
  }
}
