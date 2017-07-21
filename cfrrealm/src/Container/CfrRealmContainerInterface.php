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
 * @property \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $typeToSchema
 * @property \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator_proxy
 * @property \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
 * @property \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $schemaFormHelper
 * @property \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
 * @property \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $schemaReplacer
 * @property \Donquixote\Cf\Translator\TranslatorInterface $translator
 *
 * Non-circular:
 * @property \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
 * @property \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
 *
 * To be provided by child container:
 * @property \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
 */
interface CfrRealmContainerInterface {}
