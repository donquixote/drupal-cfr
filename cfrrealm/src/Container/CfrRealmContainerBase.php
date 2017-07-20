<?php

namespace Drupal\cfrrealm\Container;

use Donquixote\Containerkit\Container\ContainerBase;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabel;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel_FromModuleName;
use Drupal\cfrfamily\DefmapToCfrFamily\DefmapToCfrFamily;
use Drupal\cfrfamily\DefmapToCfrFamily\DefmapToCfrFamily_InlineExpanded;
use Drupal\cfrfamily\DefmapToContainer\DefmapToContainer;
use Donquixote\Cf\DefmapToDrilldownSchema\DefmapToDrilldownSchema;
use Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamily_ViaDefmap;
use Donquixote\Cf\TypeToSchema\TypeToSchema_AddTag;
use Donquixote\Cf\TypeToSchema\TypeToSchema_Buffer;
use Donquixote\Cf\TypeToSchema\TypeToSchema_InlineExpanded;
use Donquixote\Cf\TypeToSchema\TypeToSchema_ViaDefmap;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfigurator_Buffer;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfigurator_ViaCfSchema;
use Drupal\cfrrealm\TypeToContainer\TypeToContainer_Buffer;
use Drupal\cfrrealm\TypeToContainer\TypeToContainer_ViaDefmap;

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
   * @see $typeToSchema_tagged
   */
  protected function get_typeToSchema_tagged() {

    $typeToSchema = $this->typeToSchema;
    $typeToSchema = new TypeToSchema_AddTag($typeToSchema);
    $typeToSchema = new TypeToSchema_Buffer($typeToSchema);

    return $typeToSchema;
  }

  /**
   * @return \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   *
   * @see $typeToSchema
   */
  protected function get_typeToSchema() {

    $typeToSchema = new TypeToSchema_ViaDefmap(
      $this->typeToDefmap,
      $this->defmapToDrilldownSchema);

    $typeToSchema = new TypeToSchema_InlineExpanded(
      $typeToSchema,
      $this->typeToDefmap);

    # $typeToSchema = new TypeToSchema_AddTag($typeToSchema);

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
   * @return \Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamilyInterface
   *
   * @see $typeToCfrFamily
   */
  protected function get_typeToCfrFamily() {
    return new TypeToCfrFamily_ViaDefmap($this->typeToDefmap, $this->defmapToCfrFamily);
  }

  /**
   * @return \Donquixote\Cf\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface
   *
   * @see $defmapToDrilldownSchema
   */
  protected function get_defmapToDrilldownSchema() {
    return new DefmapToDrilldownSchema(
      $this->definitionToSchema_proxy,
      $this->definitionToLabel,
      $this->definitionToGrouplabel);
  }

  /**
   * @return \Drupal\cfrfamily\DefmapToCfrFamily\DefmapToCfrFamilyInterface
   *
   * @see $defmapToCfrFamily
   */
  protected function get_defmapToCfrFamily() {
    return TRUE
      ? new DefmapToCfrFamily_InlineExpanded($this->definitionToConfigurator, $this->definitionToLabel, $this->definitionToGrouplabel)
      : new DefmapToCfrFamily($this->definitionToConfigurator, $this->definitionToLabel, $this->definitionToGrouplabel);
  }

  /**
   * @return \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface
   *
   * @see $definitionToSchema
   */
  abstract protected function get_definitionToSchema();

  /**
   * @return \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   *
   * @see $definitionToConfigurator
   */
  abstract protected function get_definitionToConfigurator();

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

  /**
   * @return \Drupal\cfrrealm\TypeToContainer\TypeToContainer_Buffer
   *
   * @see $typeToContainer
   */
  protected function get_typeToContainer() {
    $typeToContainer = new TypeToContainer_ViaDefmap($this->typeToDefmap, $this->defmapToContainer);
    return new TypeToContainer_Buffer($typeToContainer);
  }

  /**
   * @return \Drupal\cfrfamily\DefmapToContainer\DefmapToContainer
   *
   * @see $defmapToContainer
   */
  protected function get_defmapToContainer() {
    return new DefmapToContainer($this->definitionToConfigurator, $this->definitionToLabel, $this->definitionToGrouplabel);
  }

}
