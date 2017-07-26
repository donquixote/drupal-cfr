<?php

namespace Drupal\cfrrealm\Container;

use Donquixote\Cf\Container\CfContainerInterface;

/**
 * Contains services that are used throughout one configurator realm.
 *
 * In the main implementation, some of them have circular dependencies, which is
 * resolved through a proxy object for $definitionToConfigurator.
 *
 * Main cycle of circular dependencies:
 *
 * @property \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $typeToConfigurator
 * @property \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator_proxy
 * @property \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
 */
interface CfrRealmContainerInterface extends CfContainerInterface {}
