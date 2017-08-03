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
 * @property \Donquixote\Cf\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
 * @property \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
 * @property \Donquixote\Cf\CachePrefix\CachePrefixInterface|null $cacheRootOrNull
 *
 * To be provided by child container:
 * @property \Donquixote\Cf\Cache\CacheInterface|null $cacheOrNull
 */
interface CfContainerInterface {

}
