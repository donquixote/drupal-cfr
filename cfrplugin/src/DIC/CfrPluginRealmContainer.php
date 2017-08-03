<?php

namespace Drupal\cfrplugin\DIC;

use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_Callback;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_DefmapDrilldown;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_IfaceDefmap;
use Donquixote\Cf\SchemaReplacer\SchemaReplacer_FromPartials;
use Donquixote\Cf\Translator\Translator_D7;
use Drupal\cfrapi\SchemaToConfigurator\Partial\SchemaToConfigurator_Proxy;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfigurator_Sta;
use Drupal\cfrplugin\TypeToConfigurator\TypeToConfigurator_CfrPlugin;
use Drupal\cfrrealm\Container\CfrRealmContainerBase;
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndId_Cache;
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndId_HookDiscovery;
use Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyid;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmap_Cache;

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
    return new self(
      \Drupal::languageManager()->getCurrentLanguage()->getId());
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
   * @return \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   *
   * @see $typeToDefmap
   */
  protected function get_typeToDefmap() {
    $typeToDefinitionsById = new TypeToDefinitionsbyid($this->definitionsByTypeAndId);
    return new TypeToDefmap_Cache($typeToDefinitionsById, 'cfrplugin:definitions:' . $this->cacheSuffix);
  }

  /**
   * @return \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface
   *
   * @see $schemaToConfigurator_proxy
   */
  protected function get_schemaToConfigurator_proxy() {

    return new SchemaToConfigurator_Proxy(
      function() {
        return $this->schemaToConfigurator;
      });
  }

  /**
   * @return \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface
   *
   * @see $schemaToConfigurator
   */
  protected function get_schemaToConfigurator() {

    return new SchemaToConfigurator_Sta($this->schemaToAnything);
  }

  /**
   * @return \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface
   *
   * @see $schemaReplacer
   */
  protected function get_schemaReplacer() {

    $partials = [];

    $partials[] = new SchemaReplacerPartial_IfaceDefmap(
      $this->typeToDefmap,
      TRUE);

    $partials[] = SchemaReplacerPartial_DefmapDrilldown::createWithInlineChildren();

    $partials[] = SchemaReplacerPartial_Callback::create();

    # $partials[] = new SchemaReplacerPartial_Transformable();

    return new SchemaReplacer_FromPartials($partials);
  }

  /**
   * @return \Donquixote\Cf\Translator\TranslatorInterface
   *
   * @see $translator
   */
  protected function get_translator() {
    return Translator_D7::createOrPassthru();
  }

}
