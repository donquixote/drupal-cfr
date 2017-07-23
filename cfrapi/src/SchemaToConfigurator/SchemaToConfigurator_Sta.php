<?php

namespace Drupal\cfrapi\SchemaToConfigurator;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface;
use Drupal\cfrapi\Exception\UnsupportedSchemaException;

class SchemaToConfigurator_Sta implements SchemaToConfiguratorInterface {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  public function __construct(SchemaToAnythingInterface $schemaToAnything) {
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetConfigurator(CfSchemaInterface $schema) {
    return $this->schemaRequireConfigurator($schema);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetOptionalConfigurator(CfSchemaInterface $schema) {

    $optionable = $this->schemaRequireOptionable($schema);

    if (NULL === $configurator = $optionable->getOptionalConfigurator()) {
      throw new UnsupportedSchemaException("->getOptionalConfigurator returned NULL.");
    }

    return $configurator;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  private function schemaRequireConfigurator(CfSchemaInterface $schema) {

    if ($schema instanceof ConfiguratorInterface) {
      return $schema;
    }

    return $this->schemaRequireObject($schema, ConfiguratorInterface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  private function schemaRequireOptionable(CfSchemaInterface $schema) {
    return $this->schemaRequireObject($schema, OptionableConfiguratorInterface::class);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  private function schemaRequireObject(CfSchemaInterface $schema, $interface) {

    $object = $this->schemaToAnything->schema(
      $schema,
      $interface);

    if (NULL === $object) {
      throw self::createException($schema, $interface);
    }

    if (!$object instanceof $interface) {
      throw self::createException($schema, $interface, $object);
    }

    return $object;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param $interface
   * @param null $instead
   *
   * @return \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  private static function createException(CfSchemaInterface $schema, $interface, $instead = NULL) {

    $schemaClass = get_class($schema);

    if (NULL === $instead) {
      return new UnsupportedSchemaException("Cannot create $interface object for schema of\nclass $schemaClass.");
    }

    if (is_object($instead)) {
      $insteadClass = get_class($instead);
      $insteadStr = "$insteadClass object";
    }
    else {
      $insteadStr = var_export($instead, TRUE);
    }

    return new UnsupportedSchemaException("Cannot create $interface object for schema of\nclass $schemaClass.\nFound $insteadStr instead.");
  }
}
