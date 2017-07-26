<?php

namespace Donquixote\Cf\Container;

/**
 * Main cycle of circular dependencies:
 * @property \Donquixote\Cf\TypeToSchema\TypeToSchemaInterface $typeToSchema
 * @property \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
 * @property \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
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
interface CfContainerInterface {

}
