<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema;

use Drupal\cfrapi\Context\CfrContextInterface;

interface DefinitionToCfrSchemaInterface {

  /**
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function definitionGetCfrSchema(array $definition, CfrContextInterface $context = NULL);

}
