<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

abstract class SchemaReplacerPartial_IfaceBase implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $schemas = [];

  /**
   * @return string
   */
  public function getSourceSchemaClass() {
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
  abstract protected function schemaDoGetReplacement(
    CfSchema_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  );
}
