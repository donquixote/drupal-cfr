<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema;

use Drupal\cfrapi\Context\CfrContextInterface;

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
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function definitionGetSchema(array $definition, CfrContextInterface $context = NULL);

}
