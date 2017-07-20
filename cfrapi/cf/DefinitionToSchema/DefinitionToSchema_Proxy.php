<?php

namespace Donquixote\Cf\DefinitionToSchema;

use Donquixote\Cf\Context\CfContextInterface;

class DefinitionToSchema_Proxy implements DefinitionToSchemaInterface {

  /**
   * @var \Donquixote\Cf\DefinitionToSchema\DefinitionToSchemaInterface|null
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
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   *
   * @throws \Donquixote\Cf\Exception\CfSchemaCreationException
   */
  public function definitionGetSchema(array $definition, CfContextInterface $context = NULL) {

    if (NULL === $this->instance) {
      $this->instance = call_user_func($this->factory);
    }

    return $this->instance->definitionGetSchema($definition, $context);
  }

}
