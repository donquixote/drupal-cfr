<?php

namespace Drupal\cfrapi\SchemaToConfigurator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;

class SchemaToConfigurator_Proxy implements SchemaToConfiguratorInterface {

  /**
   * @var callable
   */
  private $factory;

  /**
   * @var \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface|null
   */
  private $instance;

  /**
   * @param callable $factory
   */
  public function __construct($factory) {
    $this->factory = $factory;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetConfigurator(CfSchemaInterface $schema) {

    if (NULL === $this->instance) {
      $this->instance = call_user_func($this->factory);
    }

    return $this->instance->schemaGetConfigurator($schema);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetOptionalConfigurator(CfSchemaInterface $schema) {

    if (NULL === $this->instance) {
      $this->instance = call_user_func($this->factory);
    }

    return $this->instance->schemaGetOptionalConfigurator($schema);
  }
}
