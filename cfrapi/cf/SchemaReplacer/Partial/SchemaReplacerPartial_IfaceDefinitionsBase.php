<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Schema\Definitions\CfSchema_Definitions;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

abstract class SchemaReplacerPartial_IfaceDefinitionsBase extends SchemaReplacerPartial_IfaceBase {

  /**
   * @param \Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface $ifaceSchema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  protected function schemaDoGetReplacement(
    CfSchema_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  ) {

    $type = $ifaceSchema->getInterface();
    $context = $ifaceSchema->getContext();

    $definitions = $this->typeGetDefinitions($type);

    $schema = new CfSchema_Definitions($definitions, $context);

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    return $schema;
  }

  /**
   * @param string $type
   *
   * @return array[]
   */
  abstract protected function typeGetDefinitions($type);
}
