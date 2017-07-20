<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Defmap\CfSchema_Defmap;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;
use Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface;

class SchemaReplacerPartial_IfaceDefmap implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var bool
   */
  private $withTaggingDecorator;

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $schemas = [];

  /**
   * @param \Donquixote\Cf\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param bool $withInlineChildren
   * @param bool $withTaggingDecorator
   */
  public function __construct(
    TypeToDefmapInterface $typeToDefmap,
    $withInlineChildren = TRUE,
    $withTaggingDecorator = TRUE
  ) {
    $this->typeToDefmap = $typeToDefmap;
    $this->withTaggingDecorator = $withTaggingDecorator;
  }

  /**
   * @return string
   */
  public function getSourceSchemaClass() {
    // Accepts any schema.
    return CfSchema_IfaceWithContextInterface::class;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer) {

    if (!$schema instanceof CfSchema_IfaceWithContextInterface) {
      return NULL;
    }

    $k = $schema->getCacheId();

    // The value NULL does not occur, so isset() is safe.
    return isset($this->schemas[$k])
      ? $this->schemas[$k]
      : $this->schemas[$k] = $this->schemaDoGetReplacement($schema, $replacer);
  }

  /**
   * @param \Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface $ifaceSchema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private function schemaDoGetReplacement(
    CfSchema_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  ) {

    $type = $ifaceSchema->getInterface();
    $context = $ifaceSchema->getContext();

    $defmap = $this->typeToDefmap->typeGetDefmap($type);

    $schema = new CfSchema_Defmap($defmap, $context);

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    if ($this->withTaggingDecorator) {
      $schema = new CfSchema_Neutral_IfaceTransformed(
        $schema,
        $type,
        $context);
    }

    return $schema;
  }
}
