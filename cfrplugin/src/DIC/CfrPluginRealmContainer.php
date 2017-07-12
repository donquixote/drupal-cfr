<?php

namespace Drupal\cfrplugin\DIC;

use Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfigurator_FromPartial;
use Drupal\cfrapi\CfrSchemaToConfigurator\Partial\CfrSchemaToConfigurator_Proxy;
use Drupal\cfrapi\CfrSchemaToConfigurator\Partial\CfrSchemaToConfiguratorPartial_Hardcoded;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchema_Mappers;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchema_Proxy;
use Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchema_Replacer;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfigurator_Mappers;
use Drupal\cfrplugin\TypeToConfigurator\TypeToConfigurator_CfrPlugin;
use Drupal\cfrplugin\Util\ServiceFactoryUtil;
use Drupal\cfrrealm\CfrSchemaReplacer\SchemaReplacer_Hardcoded;
use Drupal\cfrrealm\Container\CfrRealmContainerBase;
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndId_Cache;
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndId_HookDiscovery;
use Drupal\cfrrealm\DefinitionToConfigurator\DefinitionToConfigurator_Proxy;
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
  public static function createWithCache() {
    return new self($GLOBALS['language']->language);
  }

  /**
   * Creates a container instance with cache disabled.
   *
   * @return \Drupal\cfrplugin\DIC\CfrPluginRealmContainer
   */
  public static function createWithoutCache() {
    return new self(NULL);
  }

  /**
   * @param string|null $cacheSuffix
   *   The langcode to append to the cache id, or NULL to have no cache.
   */
  public function __construct($cacheSuffix) {
    $this->cacheSuffix = $cacheSuffix;
  }

  /**
   * Overrides the parent implementation to add a decorator layer.
   *
   * @return \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  protected function getTypeToConfiguratorUnbuffered() {

    $typeToConfigurator = parent::getTypeToConfiguratorUnbuffered();
    $typeToConfigurator = new TypeToConfigurator_CfrPlugin($typeToConfigurator);

    return $typeToConfigurator;
  }

  /**
   * @return \Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   *
   * @see $definitionsByTypeAndId
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
   * @see $typeToDefmap
   */
  protected function get_typeToDefmap() {
    $typeToDefinitionsById = new TypeToDefinitionsbyid($this->definitionsByTypeAndId);
    return new TypeToDefmap($typeToDefinitionsById, 'cfrplugin:definitions:' . $this->cacheSuffix);
  }

  /**
   * @return \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface
   *
   * @see $definitionToCfrSchema_proxy
   */
  protected function get_definitionToCfrSchema_proxy() {
    return new DefinitionToSchema_Proxy(
      function() {
        // $this can be used since PHP 5.4.
        return $this->definitionToCfrSchema;
      });
  }

  /**
   * @return \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface
   *
   * @see $definitionToCfrSchema
   */
  protected function get_definitionToCfrSchema() {

    $definitionToCfrSchema = DefinitionToSchema_Mappers::create();

    $definitionToCfrSchema = new DefinitionToSchema_Replacer(
      $definitionToCfrSchema,
      new SchemaReplacer_Hardcoded(
        $this->typeToCfrSchema_tagged,
        $this->paramToLabel));

    return $definitionToCfrSchema;
  }

  /**
   * @return \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   *
   * @see $definitionToConfigurator
   */
  protected function get_definitionToConfigurator() {
    // Use a stub to allow circular dependency.
    return new DefinitionToConfigurator_Proxy(
      function() {
        $definitionToConfigurator = new DefinitionToConfigurator_Mappers();
        // $this can be used since PHP 5.4.
        $mappers = ServiceFactoryUtil::createDeftocfrMappers(
          $this->callbackToConfigurator,
          $this->cfrSchemaToConfigurator);
        foreach ($mappers as $key => $mapper) {
          $definitionToConfigurator->keySetMapper($key, $mapper);
        }
        return $definitionToConfigurator;
      });
  }

  /**
   * @return \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   *
   * @see $cfrSchemaToConfigurator_proxy
   */
  protected function get_cfrSchemaToConfigurator_proxy() {
    return new CfrSchemaToConfigurator_Proxy(
      function() {
        return $this->cfrSchemaToConfigurator;
      });
  }

  /**
   * @return \Drupal\cfrapi\CfrSchemaToConfigurator\CfrSchemaToConfiguratorInterface
   *
   * @see $cfrSchemaToConfigurator
   */
  protected function get_cfrSchemaToConfigurator() {
    return new CfrSchemaToConfigurator_FromPartial(
      new CfrSchemaToConfiguratorPartial_Hardcoded(
        $this->typeToConfigurator,
        $this->paramToConfigurator,
        $this->paramToLabel));
  }

  /**
   * @return \Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfigurator_ValueCallback
   *
   * @see $callbackToConfigurator
   */
  protected function get_callbackToConfigurator() {
    return new CallbackToConfigurator_ValueCallback($this->paramToConfigurator, $this->paramToLabel);
  }

  /**
   * @return \Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfigurator
   *
   * @see $paramToConfigurator
   */
  protected function get_paramToConfigurator() {
    return new ParamToConfigurator($this->typeToConfigurator);
  }

  /**
   * @return \Drupal\cfrreflection\ParamToLabel\ParamToLabel
   *
   * @see $paramToLabel
   */
  protected function get_paramToLabel() {
    return new ParamToLabel();
  }

}
