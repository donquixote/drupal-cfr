<?php

namespace Donquixote\Cf\Legacy\SchemaToSomething\Partial;

use Donquixote\Cf\Legacy\SchemaToSomething\Helper\SchemaToSomethingHelperInterface;
use Donquixote\Cf\Legacy\SchemaToSomething\SchemaToSomethingTrait;
use Donquixote\Cf\Schema\CfSchemaInterface;

abstract class SchemaToSomethingPartialBase implements SchemaToSomethingPartialInterface {

  use SchemaToSomethingTrait;


  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\Legacy\SchemaToSomething\Helper\SchemaToSomethingHelperInterface $helper
   *
   * @return null|object
   */
  public function schema(CfSchemaInterface $schema, SchemaToSomethingHelperInterface $helper) {

    $candidate = $this->schemaGetCandidate($schema, $helper);

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
   * @param \Donquixote\Cf\Legacy\SchemaToSomething\Helper\SchemaToSomethingHelperInterface $helper
   *
   * @return null|object
   */
  abstract protected function schemaGetCandidate(CfSchemaInterface $schema, SchemaToSomethingHelperInterface $helper);
}
