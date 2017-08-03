<?php

namespace Donquixote\Cf\Container;

use Donquixote\Cf\CachePrefix\CachePrefix_Root;
use Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndId_Cache;
use Donquixote\Cf\DefinitionToLabel\DefinitionToLabel;
use Donquixote\Cf\ParamToValue\ParamToValue_ObjectsMatchType;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_Callback;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_DefmapDrilldown;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_IfaceDefmap;
use Donquixote\Cf\SchemaReplacer\SchemaReplacer_FromPartials;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelper;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_SchemaReplacer;
use Donquixote\Cf\Translator\Translator;
use Donquixote\Cf\TypeToDefinitionsbyid\TypeToDefinitionsbyid;
use Donquixote\Cf\TypeToDefmap\TypeToDefmap;
use Donquixote\Cf\TypeToDefmap\TypeToDefmap_Cache;
use Donquixote\Cf\TypeToSchema\TypeToSchema_Buffer;
use Donquixote\Cf\TypeToSchema\TypeToSchema_Iface;
use Donquixote\Cf\Util\LocalPackageUtil;
use Donquixote\Containerkit\Container\ContainerBase;

abstract class CfContainerBase extends ContainerBase implements CfContainerInterface {

  /**
   * @return \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface
   *
   * @see $typeToSchema
   */
  protected function get_typeToSchema() {

    $typeToSchema = new TypeToSchema_Iface();
    $typeToSchema = new TypeToSchema_Buffer($typeToSchema);

    return $typeToSchema;
  }

  /**
   * @return \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   *
   * @see $schemaToAnything
   */
  protected function get_schemaToAnything() {

    $partials = $this->getSTAPartials();

    $partials[] = new SchemaToAnythingPartial_SchemaReplacer(
      $this->schemaReplacer);

    return SchemaToAnythingHelper::createFromPartials($partials);
  }

  /**
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface[]
   */
  protected function getSTAPartials() {

    return LocalPackageUtil::collectSTAPartials($this->paramToValue);
  }

  /**
   * @return \Donquixote\Cf\ParamToValue\ParamToValueInterface
   *
   * @see $paramToValue
   */
  protected function get_paramToValue() {

    return new ParamToValue_ObjectsMatchType(
      [
        $this->translator,
      ]);
  }

  /**
   * @return \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface
   *
   * @see $schemaReplacer
   */
  protected function get_schemaReplacer() {

    $partials = $this->getSchemaReplacerPartials();

    return new SchemaReplacer_FromPartials($partials);
  }

  /**
   * @return \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
   */
  protected function getSchemaReplacerPartials() {

    $partials = [];

    $partials[] = new SchemaReplacerPartial_IfaceDefmap(
      $this->typeToDefmap,
      TRUE);

    $partials[] = SchemaReplacerPartial_DefmapDrilldown::createWithInlineChildren();

    $partials[] = SchemaReplacerPartial_Callback::create();

    return $partials;
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
   * @return \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToLabel
   */
  protected function get_definitionToLabel() {
    return DefinitionToLabel::create();
  }

  /**
   * @return \Donquixote\Cf\DefinitionToLabel\DefinitionToLabelInterface
   *
   * @see $definitionToGrouplabel
   */
  protected function get_definitionToGrouplabel() {
    return DefinitionToLabel::createGroupLabel();
  }

  /**
   * @return \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   *
   * @see $typeToDefmap
   */
  protected function get_typeToDefmap() {

    $typeToDefinitionsById = new TypeToDefinitionsbyid(
      $this->definitionsByTypeAndId);

    if (NULL !== $cacheRoot = $this->cacheRootOrNull) {
      return new TypeToDefmap($typeToDefinitionsById);
    }

    return new TypeToDefmap_Cache(
      $typeToDefinitionsById,
      $cacheRoot->withAppendedPrefix(
        $this->getDefinitionsCachePrefix()));
  }

  /**
   * @return string
   */
  protected function getDefinitionsCachePrefix() {
    return 'definitions:';
  }

  /**
   * @return \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   *
   * @see $definitionsByTypeAndId
   */
  protected function get_definitionsByTypeAndId() {

    $definitionsByTypeAndId = $this->getDefinitionDiscovery();

    if (NULL === $cacheRoot = $this->cacheRootOrNull) {
      return $definitionsByTypeAndId;
    }

    return new DefinitionsByTypeAndId_Cache(
      $definitionsByTypeAndId,
      $cacheRoot->getOffset(
        $this->getDefinitionsCacheKey()));
  }

  /**
   * @return string
   */
  protected function getDefinitionsCacheKey() {
    return 'definitions-all:';
  }

  /**
   * @return \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  abstract protected function getDefinitionDiscovery();

  /**
   * @return \Donquixote\Cf\CachePrefix\CachePrefixInterface|null
   *
   * @see $cacheRootOrNull
   */
  protected function get_cacheRootOrNull() {

    if (NULL === ($cache = $this->cacheOrNull)) {
      return NULL;
    }

    $root = new CachePrefix_Root($this->cacheOrNull);

    if ('' === $prefix = $this->getCachePrefix()) {
      return $root;
    }

    return $root->withAppendedPrefix($prefix);
  }

  /**
   * @return string
   */
  protected function getCachePrefix() {
    return '';
  }

  /**
   * @return \Donquixote\Cf\Cache\CacheInterface|null
   *
   * @see $cacheOrNull
   */
  protected function get_cacheOrNull() {
    return NULL;
  }

}
