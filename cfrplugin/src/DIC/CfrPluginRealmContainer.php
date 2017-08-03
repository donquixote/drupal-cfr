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
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndId_HookDiscovery;

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
      \Drupal::languageManager()->getCurrentLanguage()->getId() . ':');
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
   * @return \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  protected function getDefinitionDiscovery() {
    return new DefinitionsByTypeAndId_HookDiscovery('cfrplugin_info');
  }

  /**
   * @return string
   */
  protected function getDefinitionsCachePrefix() {
    return parent::getDefinitionsCachePrefix() . $this->cacheSuffix;
  }

  /**
   * @return string
   */
  protected function getDefinitionsCacheKey() {
    return parent::getDefinitionsCacheKey() . $this->cacheSuffix;
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

  /**
   * @return string
   */
  protected function getCachePrefix() {
    return 'cfrplugin:';
  }

}
