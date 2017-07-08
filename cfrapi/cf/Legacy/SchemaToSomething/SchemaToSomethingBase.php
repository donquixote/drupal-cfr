<?php

namespace Donquixote\Cf\Legacy\SchemaToSomething;

use Donquixote\Cf\Schema\CfSchemaInterface;

abstract class SchemaToSomethingBase implements SchemaToSomethingInterface {

  use SchemaToSomethingTrait;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return null|object
   */
  public function schema(CfSchemaInterface $schema) {

    $candidate = $this->schemaGetCandidate($schema);

    if (NULL === $candidate) {
      return NULL;
    }

    if (!$candidate instanceof $this->resultInterface) {
      return NULL;
    }

    return $candidate;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return null|object
   */
  abstract protected function schemaGetCandidate(CfSchemaInterface $schema);
}
