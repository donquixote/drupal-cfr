<?php

namespace Drupal\cfrrealm\Container;

use Donquixote\Containerkit\Container\ContainerBase;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel_FromModuleName;
use Drupal\cfrfamily\DefmapToCfrFamily\DefmapToCfrFamily;
use Drupal\cfrfamily\DefmapToCfrFamily\DefmapToCfrFamily_InlineExpanded;
use Drupal\cfrfamily\DefmapToContainer\DefmapToContainer;
use Drupal\cfrfamily\DefmapToDrilldownSchema\DefmapToDrilldownSchema;
use Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamily_ViaDefmap;
use Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchema_AddTag;
use Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchema_Buffer;
use Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchema_InlineExpanded;
use Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchema_ViaDefmap;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfigurator_Buffer;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfigurator_ViaCfrSchema;
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
    return new TypeToConfigurator_ViaCfrSchema(
      $this->typeToCfrSchema,
      $this->cfrSchemaToConfigurator_proxy);
  }

  /**
   * @return \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface
   *
   * @see $typeToCfrSchema_tagged
   */
  protected function get_typeToCfrSchema_tagged() {

    $typeToCfrSchema = $this->typeToCfrSchema;
    $typeToCfrSchema = new TypeToCfrSchema_AddTag($typeToCfrSchema);
    $typeToCfrSchema = new TypeToCfrSchema_Buffer($typeToCfrSchema);

    return $typeToCfrSchema;
  }

  /**
   * @return \Drupal\cfrrealm\TypeToCfrSchema\TypeToCfrSchemaInterface
   *
   * @see $typeToCfrSchema
   */
  protected function get_typeToCfrSchema() {

    $typeToCfrSchema = new TypeToCfrSchema_ViaDefmap(
      $this->typeToDefmap,
      $this->defmapToDrilldownSchema);

    $typeToCfrSchema = new TypeToCfrSchema_InlineExpanded(
      $typeToCfrSchema,
      $this->typeToDefmap);

    # $typeToCfrSchema = new TypeToCfrSchema_AddTag($typeToCfrSchema);

    $typeToCfrSchema = new TypeToCfrSchema_Buffer($typeToCfrSchema);

    return $typeToCfrSchema;
  }

  /**
   * @return \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   *
   * @see $cfrSchemaToConfigurator_proxy
   */
  abstract protected function get_cfrSchemaToConfigurator_proxy();

  /**
   * @return \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   *
   * @see $cfrSchemaToConfigurator
   */
  abstract protected function get_cfrSchemaToConfigurator();

  /**
   * @return \Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamilyInterface
   *
   * @see $typeToCfrFamily
   */
  protected function get_typeToCfrFamily() {
    return new TypeToCfrFamily_ViaDefmap($this->typeToDefmap, $this->defmapToCfrFamily);
  }

  /**
   * @return \Drupal\cfrfamily\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface
   *
   * @see $defmapToDrilldownSchema
   */
  protected function get_defmapToDrilldownSchema() {
    return new DefmapToDrilldownSchema(
      $this->definitionToCfrSchema_proxy,
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
   * @return \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToCfrSchemaInterface
   *
   * @see $definitionToCfrSchema
   */
  abstract protected function get_definitionToCfrSchema();

  /**
   * @return \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   *
   * @see $definitionToConfigurator
   */
  abstract protected function get_definitionToConfigurator();

  /**
   * @return \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel
   *
   * @see $definitionToLabel
   */
  protected function get_definitionToLabel() {
    return DefinitionToLabel::create();
  }

  /**
   * @return \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel_FromModuleName
   *
   * @see $definitionToGrouplabel
   */
  protected function get_definitionToGrouplabel() {
    return new DefinitionToLabel_FromModuleName();
  }

  /**
   * @return \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface
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
