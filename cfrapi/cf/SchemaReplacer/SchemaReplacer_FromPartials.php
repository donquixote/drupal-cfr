<?php

namespace Donquixote\Cf\SchemaReplacer;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface;

class SchemaReplacer_FromPartials implements SchemaReplacerInterface {

  /**
   * @var \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\Cf\SchemaReplacer\Partial\SchemaReplacerPartialInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   *   A transformed schema, or NULL if no replacement can be found.
   */
  public function schemaGetReplacement(CfSchemaInterface $schema) {

    foreach ($this->partials as $partial) {
      if (NULL !== $replacement = $partial->schemaGetReplacement($schema, $this)) {
        return $replacement;
      }
    }

    return NULL;
  }
}
