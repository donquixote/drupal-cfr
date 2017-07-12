<?php

namespace Drupal\cfrrealm\Container;

/**
 * Contains services that are used throughout one configurator realm.
 *
 * In the main implementation, some of them have circular dependencies, which is
 * resolved through a proxy object for $definitionToConfigurator.
 *
 * Main cycle of circular dependencies:
 *
 * @property \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $typeToConfigurator
 * @property \Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamilyInterface $typeToCfrFamily
 * @property \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $typeToCfrSchema_tagged
 * @property \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $typeToCfrSchema
 * @property \Drupal\cfrfamily\DefmapToDrilldownSchema\DefmapToDrilldownSchemaInterface $defmapToDrilldownSchema
 * @property \Drupal\cfrfamily\DefmapToCfrFamily\DefmapToCfrFamilyInterface $defmapToCfrFamily
 * @property \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface $definitionToConfigurator
 * @property \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface $definitionToCfrSchema_proxy
 * @property \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface $definitionToCfrSchema
 * @property \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator_proxy
 * @property \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface $cfrSchemaToConfigurator
 *
 * Non-circular:
 * @property \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
 * @property \Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
 *
 * To be provided by child container:
 * @property \Drupal\cfrrealm\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
 *
 * Deprecation candidate:
 * @property \Drupal\cfrrealm\TypeToContainer\TypeToContainerInterface $typeToContainer
 * @property \Drupal\cfrfamily\DefmapToContainer\DefmapToContainerInterface $defmapToContainer
 */
interface CfrRealmContainerInterface {}
