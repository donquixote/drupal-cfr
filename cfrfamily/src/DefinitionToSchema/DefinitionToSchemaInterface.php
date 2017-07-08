<?php

namespace Drupal\cfrfamily\DefinitionToSchema;

use Drupal\cfrapi\Context\CfrContextInterface;

interface DefinitionToSchemaInterface {

  /**
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function definitionGetSchema(array $definition, CfrContextInterface $context = NULL);

}
