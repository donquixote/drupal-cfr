<?php

namespace Drupal\cfrrealm\Container;

use Donquixote\Cf\Container\CfContainerBase;
use Drupal\cfrapi\Cache\Cache_D7;
use Drupal\cfrapi\Util\DrupalSTAUtil;
use Drupal\cfrfamily\DefinitionToLabel\DefinitionToLabel_FromModuleName;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfigurator_Buffer;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfigurator_ViaCfSchema;

/**
 * Contains services that are used throughout one configurator realm.
 */
abstract class CfrRealmContainerBase extends CfContainerBase implements CfrRealmContainerInterface {

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
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  protected function getSTAPartials() {

    $partialsCore =  parent::getSTAPartials();
    $partialsDrupal = DrupalSTAUtil::collectSTAPartials($this->paramToValue);

    return array_merge($partialsCore, $partialsDrupal);
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
   * @return \Donquixote\Cf\Cache\CacheInterface
   *
   * @see $cacheOrNull
   */
  protected function get_cacheOrNull() {
    return new Cache_D7();
  }

}
