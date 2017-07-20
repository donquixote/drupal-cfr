<?php

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_TransformableInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_Transformable implements SchemaReplacerPartialInterface {

  /**
   * @return string
   */
  public function getSourceSchemaClass() {
    return CfSchema_TransformableInterface::class;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer) {

    if (!$schema instanceof CfSchema_TransformableInterface) {
      return NULL;
    }

    return $schema->withReplacements($replacer);
  }
}
