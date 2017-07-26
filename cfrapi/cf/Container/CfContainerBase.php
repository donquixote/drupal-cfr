<?php

namespace Donquixote\Cf\Container;

use Donquixote\Cf\DefinitionToLabel\DefinitionToLabel;
use Donquixote\Cf\ParamToValue\ParamToValue_ObjectsMatchType;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_Callback;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_DefmapDrilldown;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartial_IfaceDefmap;
use Donquixote\Cf\SchemaReplacer\SchemaReplacer_FromPartials;
use Donquixote\Cf\SchemaToAnything\Helper\SchemaToAnythingHelper;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_SchemaReplacer;
use Donquixote\Cf\Translator\Translator;
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
  abstract protected function get_typeToDefmap();

}
