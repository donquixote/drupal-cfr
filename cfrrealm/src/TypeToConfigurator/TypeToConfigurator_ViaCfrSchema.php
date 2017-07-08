<?php

namespace Drupal\cfrrealm\TypeToConfigurator;

use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface;
use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator_Exception;
use Drupal\cfrapi\Configurator\Broken\OptionalBrokenConfigurator_Exception;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;
use Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface;

class TypeToConfigurator_ViaCfrSchema implements TypeToConfiguratorInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface
   */
  private $typeToCfrSchema;

  /**
   * @var \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   */
  private $cfrSchemaToConfigurator;

  /**
   * @param \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface $typeToCfrSchema
   * @param \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
   */
  public function __construct(
    TypeToCfrSchemaInterface $typeToCfrSchema,
    CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
  ) {
    $this->typeToCfrSchema = $typeToCfrSchema;
    $this->cfrSchemaToConfigurator = $cfrSchemaToConfigurator;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function typeGetConfigurator($type, CfrContextInterface $context = NULL) {
    $schema = $this->typeToCfrSchema->typeGetCfrSchema($type, $context);
    try {
      return $this->cfrSchemaToConfigurator->cfrSchemaGetConfigurator($schema);
    }
    catch (ConfiguratorCreationException $e) {
      return new BrokenConfigurator_Exception($e);
    }
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function typeGetOptionalConfigurator($type, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    $schema = $this->typeToCfrSchema->typeGetCfrSchema($type, $context);
    try {
      return $this->cfrSchemaToConfigurator->cfrSchemaGetOptionalConfigurator($schema);
    }
    catch (ConfiguratorCreationException $e) {
      return new OptionalBrokenConfigurator_Exception($e);
    }
  }
}
