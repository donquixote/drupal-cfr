<?php

namespace Drupal\cfrfamily\DefinitionToCfrSchema;

use Drupal\cfrapi\Context\CfrContextInterface;

class DefinitionToSchema_Proxy implements DefinitionToSchemaInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionToCfrSchema\DefinitionToSchemaInterface|null
   */
  private $instance;

  /**
   * @var callable
   */
  private $factory;

  /**
   * @param callable $factory
   */
  public function __construct($factory) {
    $this->factory = $factory;
  }

  /**
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Drupal\cfrapi\Exception\SchemaCreationException
   */
  public function definitionGetSchema(array $definition, CfrContextInterface $context = NULL) {

    if (NULL === $this->instance) {
      $this->instance = call_user_func($this->factory);
    }

    return $this->instance->definitionGetSchema($definition, $context);
  }

}
