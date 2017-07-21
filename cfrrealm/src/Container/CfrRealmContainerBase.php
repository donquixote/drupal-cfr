<?php

namespace Drupal\cfrrealm\Container;

use Donquixote\Cf\DefinitionToLabel\DefinitionToLabel;
use Donquixote\Cf\TypeToSchema\TypeToSchema_Buffer;
use Donquixote\Cf\TypeToSchema\TypeToSchema_Iface;
use Donquixote\Containerkit\Container\ContainerBase;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel_FromModuleName;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfigurator_Buffer;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfigurator_ViaCfSchema;

/**
 * Contains services that are used throughout one configurator realm.
 */
abstract class CfrRealmContainerBase extends ContainerBase implements CfrRealmContainerInterface {

  /**
   * @return \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   *
   * @see $typeToConfigurator
   */
  protected function get_typeToConfigurator() {

    $typeToConfigurator = $this->getTypeToConfiguratorUnbuffered();

    $typeToConfigurator = new TypeToConfigurator_Buffer($typeToConfigurator);

    return $typeToConfigurator;
  }

  /**
   * @return \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  protected function getTypeToConfiguratorUnbuffered() {
    return new TypeToConfigurator_ViaCfSchema(
      $this->typeToSchema,
      $this->schemaToConfigurator_proxy);
  }

  /**
   * @return \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   *
   * @see $typeToSchema
   */
  protected function get_typeToSchema() {

    $typeToSchema = new TypeToSchema_Iface();
    $typeToSchema = new TypeToSchema_Buffer($typeToSchema);

    return $typeToSchema;
  }

  /**
   * @return \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface
   *
   * @see $schemaToConfigurator_proxy
   */
  abstract protected function get_schemaToConfigurator_proxy();

  /**
   * @return \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface
   *
   * @see $schemaToConfigurator
   */
  abstract protected function get_schemaToConfigurator();

  /**
   * @return \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToLabel
   */
  protected function get_definitionToLabel() {
    return DefinitionToLabel::create();
  }

  /**
   * @return \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToGrouplabel
   */
  protected function get_definitionToGrouplabel() {
    return new DefinitionToLabel_FromModuleName();
  }

  /**
   * @return \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   *
   * @see $typeToDefmap
   */
  abstract protected function get_typeToDefmap();

}
