<?php

namespace Drupal\cfrrealm\TypeToConfigurator;

use Donquixote\Cf\TypeToSchema\TypeToSchemaInterface;
use Drupal\cfrapi\CfContext\CfContext_FromCfrContext;
use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator_Exception;
use Drupal\cfrapi\Configurator\Broken\OptionalBrokenConfigurator_Exception;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;

class TypeToConfigurator_ViaCfSchema implements TypeToConfiguratorInterface {

  /**
   * @var \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   */
  private $typeToSchema;

  /**
   * @var \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface
   */
  private $schemaToConfigurator;

  /**
   * @param \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $typeToSchema
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   */
  public function __construct(
    TypeToSchemaInterface $typeToSchema,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    $this->typeToSchema = $typeToSchema;
    $this->schemaToConfigurator = $schemaToConfigurator;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function typeGetConfigurator($type, CfrContextInterface $context = NULL) {
    $schema = $this->typeToSchema->typeGetSchema($type, $context);
    try {
      return $this->schemaToConfigurator->schemaGetConfigurator($schema);
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

    $cfContext = NULL !== $context
      ? new CfContext_FromCfrContext($context)
      : NULL;

    $schema = $this->typeToSchema->typeGetSchema($type, $cfContext);

    try {
      return $this->schemaToConfigurator->schemaGetOptionalConfigurator($schema);
    }
    catch (ConfiguratorCreationException $e) {
      return new OptionalBrokenConfigurator_Exception($e);
    }
  }
}
