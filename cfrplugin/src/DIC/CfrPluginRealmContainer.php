<?php

namespace Drupal\cfrplugin\DIC;

use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Mappers;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Proxy;
use Donquixote\Cf\DefinitionToSchema\DefinitionToSchema_Replacer;
use Donquixote\Cf\Discovery\AnnotatedFactoryIA\AnnotatedFactoriesIA;
use Donquixote\Cf\Discovery\ClassFilesIA_NamespaceDirectory;
use Donquixote\Cf\Discovery\NamespaceDirectory;
use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelper_SchemaToAnything;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelper_SchemaToAnything;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelper_SchemaToAnything;
use Donquixote\Cf\ParamToLabel\ParamToLabel;
use Donquixote\Cf\SchemaReplacer\SchemaReplacer_Hardcoded;
use Donquixote\Cf\SchemaToAnything\SchemaToAnything_Chain;
use Donquixote\Cf\SchemaToEmptyness\SchemaToEmptyness_Hardcoded;
use Donquixote\Cf\Summarizer\Helper\SummaryHelper_SchemaToAnything;
use Donquixote\Cf\Translator\Translator;
use Donquixote\Cf\Util\LocalPackageUtil;
use Donquixote\Cf\Util\STAMappersUtil;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;
use Drupal\cfrapi\SchemaToConfigurator\Partial\SchemaToConfigurator_Proxy;
use Drupal\cfrapi\SchemaToConfigurator\Partial\SchemaToConfiguratorPartial_Hardcoded;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfigurator_FromPartial;
use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfigurator_Helpers;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfigurator_Mappers;
use Drupal\cfrplugin\TypeToConfigurator\TypeToConfigurator_CfrPlugin;
use Drupal\cfrplugin\Util\ServiceFactoryUtil;
use Drupal\cfrrealm\Container\CfrRealmContainerBase;
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndId_Cache;
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndId_HookDiscovery;
use Drupal\cfrrealm\DefinitionToConfigurator\DefinitionToConfigurator_Proxy;
use Drupal\cfrrealm\TypeToDefinitionsbyid\TypeToDefinitionsbyid;
use Drupal\cfrrealm\TypeToDefmap\TypeToDefmap_Cache;
use Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfigurator_ValueCallback;
use Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfigurator;

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
   * @return \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   *
   * @see $typeToDefmap
   */
  protected function get_typeToDefmap() {
    $typeToDefinitionsById = new TypeToDefinitionsbyid($this->definitionsByTypeAndId);
    return new TypeToDefmap_Cache($typeToDefinitionsById, 'cfrplugin:definitions:' . $this->cacheSuffix);
  }

  /**
   * @return \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface
   *
   * @see $definitionToSchema_proxy
   */
  protected function get_definitionToSchema_proxy() {
    return new DefinitionToSchema_Proxy(
      function() {
        // $this can be used since PHP 5.4.
        return $this->definitionToSchema;
      });
  }

  /**
   * @return \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface
   *
   * @see $definitionToSchema
   */
  protected function get_definitionToSchema() {

    $definitionToSchema = DefinitionToSchema_Mappers::create();

    $definitionToSchema = new DefinitionToSchema_Replacer(
      $definitionToSchema,
      new SchemaReplacer_Hardcoded(
        $this->typeToSchema_tagged,
        $this->paramToLabel));

    return $definitionToSchema;
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
          $this->schemaToConfigurator);
        foreach ($mappers as $key => $mapper) {
          $definitionToConfigurator->keySetMapper($key, $mapper);
        }
        return $definitionToConfigurator;
      });
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

    return new SchemaToConfigurator_Helpers(
      new ConfToValueHelper_SchemaToAnything($this->schemaToAnything),
      new ConfToPhpHelper_SchemaToAnything($this->schemaToAnything),
      new D7FormatorHelper_SchemaToAnything(
        $this->schemaToAnything,
        $this->translator),
      new SummaryHelper_SchemaToAnything(
        $this->schemaToAnything,
        $this->translator),
      new SchemaToEmptyness_Hardcoded());
  }

  /**
   * @return \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface
   *
   * @see $schemaFormHelper
   */
  protected function get_schemaFormHelper() {

    return new D7FormatorHelper_SchemaToAnything(
      $this->schemaToAnything,
      $this->translator);
  }

  /**
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   *
   * @see $schemaToAnything
   */
  protected function get_schemaToAnything() {

    // Search all of cfrapi module.
    $nsdir = NamespaceDirectory::createFromClass(ConfToValueInterface::class)
      ->parent();

    $classFilesIA = ClassFilesIA_NamespaceDirectory::createFromNsdirObject($nsdir);

    $factoriesIA = new AnnotatedFactoriesIA(
      $classFilesIA,
      'Cf');

    $mappersCore = LocalPackageUtil::collectSTAMappers();
    $mappersDrupal = STAMappersUtil::collectSTAMappers($factoriesIA);

    $mappers = array_merge($mappersCore, $mappersDrupal);

    # kdpm(get_defined_vars());

    return new SchemaToAnything_Chain($mappers);
  }

  /**
   * @return \Donquixote\Cf\Translator\TranslatorInterface
   *
   * @see $translator
   */
  protected function get_translator() {
    return Translator::createPassthru();
  }

  /**
   * @return \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface
   *
   * @see $schemaToConfigurator
   */
  protected function _get_schemaToConfigurator() {
    return new SchemaToConfigurator_FromPartial(
      new SchemaToConfiguratorPartial_Hardcoded(
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
   * @return \Donquixote\Cf\ParamToLabel\ParamToLabel
   *
   * @see $paramToLabel
   */
  protected function get_paramToLabel() {
    return new ParamToLabel();
  }

}
