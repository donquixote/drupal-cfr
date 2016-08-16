<?php

namespace Drupal\cfrplugin\DIC;

use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfigurator_Mappers;
use Drupal\cfrplugin\Util\ServiceFactoryUtil;
use Drupal\cfrrealm\DefinitionToConfigurator\DefinitionToConfigurator_Proxy;
use Drupal\cfrplugin\InterfaceToConfigurator\InterfaceToConfigurator_ViaTypeIdentity;
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndId_Cache;
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndId_HookDiscovery;
use Drupal\cfrrealm\Container\CfrRealmContainerBase;
use Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyid;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmap;
use Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfigurator_ValueCallback;
use Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfigurator;
use Drupal\cfrreflection\ParamToLabel\ParamToLabel;

class CfrPluginRealmContainer extends CfrRealmContainerBase implements CfrPluginRealmContainerInterface {

  /**
   * @var string|null
   */
  private $cacheSuffix;

  /**
   * Creates a container instance with cache enabled.
   *
   * @return \Drupal\cfrplugin\DIC\CfrPluginRealmContainer
   */
  static function createWithCache() {
    return new self($GLOBALS['language']->language);
  }

  /**
   * Creates a container instance with cache disabled.
   *
   * @return \Drupal\cfrplugin\DIC\CfrPluginRealmContainer
   */
  static function createWithoutCache() {
    return new self(NULL);
  }

  /**
   * @param string|null $cacheSuffix
   *   The langcode to append to the cache id, or NULL to have no cache.
   */
  function __construct($cacheSuffix) {
    $this->cacheSuffix = $cacheSuffix;
  }

  /**
   * @return \Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface
   *
   * @see \Drupal\cfrrealm\Container\CfrRealmContainerInterface::$interfaceToConfigurator
   */
  protected function get_interfaceToConfigurator() {
    return new InterfaceToConfigurator_ViaTypeIdentity($this->typeToConfigurator);
  }

  /**
   * @return \Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   *
   * @see \Drupal\cfrplugin\DIC\CfrPluginRealmContainer::$definitionsByTypeAndId
   */
  protected function get_definitionsByTypeAndId() {
    $definitionsByTypeAndId = new DefinitionsByTypeAndId_HookDiscovery('cfrplugin_info');
    if (NULL !== $this->cacheSuffix) {
      $definitionsByTypeAndId = new DefinitionsByTypeAndId_Cache($definitionsByTypeAndId, 'cfrplugin:definitions-all:' . $this->cacheSuffix);
    }
    return $definitionsByTypeAndId;
  }

  /**
   * @return \Drupal\cfrrealm\TypeToDefmap\TypeToDefmap
   *
   * @see \Drupal\cfrrealm\Container\CfrRealmContainerBase::$typeToDefmap
   */
  protected function get_typeToDefmap() {
    $typeToDefinitionsById = new TypeToDefinitionsbyid($this->definitionsByTypeAndId);
    return new TypeToDefmap($typeToDefinitionsById, $this->cacheSuffix);
  }

  /**
   * @return \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   *
   * @see \Drupal\cfrrealm\Container\CfrRealmContainerInterface::$definitionToConfigurator
   */
  protected function get_definitionToConfigurator() {
    // Use a stub to allow circular dependency.
    return new DefinitionToConfigurator_Proxy(
      function() {
        $definitionToConfigurator = new DefinitionToConfigurator_Mappers();
        // $this can be used since PHP 5.4.
        $mappers = ServiceFactoryUtil::createDeftocfrMappers($this->callbackToConfigurator);
        foreach ($mappers as $key => $mapper) {
          $definitionToConfigurator->keySetMapper($key, $mapper);
        }
        return $definitionToConfigurator;
      });
  }

  /**
   * @return \Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfigurator_ValueCallback
   *
   * @see \Drupal\cfrplugin\DIC\CfrPluginRealmContainerInterface::$callbackToConfigurator
   */
  protected function get_callbackToConfigurator() {
    return new CallbackToConfigurator_ValueCallback($this->paramToConfigurator, $this->paramToLabel);
  }

  /**
   * @return \Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfigurator
   *
   * @see \Drupal\cfrplugin\DIC\CfrPluginRealmContainerInterface::$paramToConfigurator
   */
  protected function get_paramToConfigurator() {
    return new ParamToConfigurator($this->interfaceToConfigurator);
  }

  /**
   * @return \Drupal\cfrreflection\ParamToLabel\ParamToLabel
   *
   * @see \Drupal\cfrplugin\DIC\CfrPluginRealmContainerInterface::$paramToLabel
   */
  protected function get_paramToLabel() {
    return new ParamToLabel();
  }

}
