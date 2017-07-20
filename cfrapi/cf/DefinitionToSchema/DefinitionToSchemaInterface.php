<?php

namespace Donquixote\Cf\DefinitionToSchema;

use Donquixote\Cf\Context\CfContextInterface;

/**
 * Objects to create schema based on definitions.
 *
 * Definitions arrays are the format in which components register their plugins.
 */
interface DefinitionToSchemaInterface {

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
  public function definitionGetSchema(array $definition, CfContextInterface $context = NULL);

}