<?php

namespace Donquixote\Cf\DefinitionToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\Definition\CfSchema_Definition;

class DefinitionToSchema_Simple implements DefinitionToSchemaInterface {

  /**
   * Gets or creates a schema object from a given definition array.
   *
   * @param array $definition
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function definitionGetSchema(array $definition, CfContextInterface $context = NULL) {
    return new CfSchema_Definition($definition, $context);
  }
}
