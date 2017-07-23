<?php

namespace Donquixote\Cf\Schema\Callback;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Donquixote\Cf\Context\CfContextInterface;

class CfSchema_Callback implements CfSchema_CallbackInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  private $explicitSchemas = [];

  /**
   * @var string[]
   */
  private $explicitLabels = [];

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param string $class
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\Callback\CfSchema_Callback
   */
  public static function fromClass($class, CfContextInterface $context = NULL) {

    return new self(
      CallbackReflection_ClassConstruction::create($class),
      $context);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return self
   */
  public static function fromStaticMethod($class, $methodName, CfContextInterface $context = NULL) {

    return new self(
      CallbackReflection_StaticMethod::create(
        $class,
        $methodName),
      $context);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    CallbackReflectionInterface $callbackReflection,
    CfContextInterface $context = NULL
  ) {
    $this->callbackReflection = $callbackReflection;
    $this->context = $context;
  }

  /**
   * @param \Donquixote\Cf\Context\CfContextInterface|NULL $context
   *
   * @return static
   */
  public function withContext(CfContextInterface $context = NULL) {
    $clone = clone $this;
    $clone->context = $context;
    return $clone;
  }

  /**
   * @param int $index
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string|null $label
   *
   * @return static
   */
  public function withParamSchema($index, CfSchemaInterface $schema, $label = NULL) {
    $clone = clone $this;
    $clone->explicitSchemas[$index] = $schema;
    if (NULL !== $label) {
      $clone->explicitLabels[$index] = $label;
    }
    return $clone;
  }

  /**
   * @param int $index
   * @param string $label
   *
   * @return static
   */
  public function withParamLabel($index, $label) {
    $clone = clone $this;
    $clone->explicitLabels[$index] = $label;
    return $clone;
  }

  /**
   * @param int $index
   * @param string $interface
   * @param string|null $label
   *
   * @return static
   */
  public function withParam_Iface($index, $interface, $label = NULL) {
    return $this->withParamSchema(
      $index,
      new CfSchema_IfaceWithContext($interface, $this->context),
      $label);
  }

  /**
   * @param int $index
   * @param string $interface
   * @param string|null $label
   *
   * @return static
   */
  public function withParam_IfaceSequence($index, $interface, $label = NULL) {
    return $this->withParamSchema(
      $index,
      CfSchema_IfaceWithContext::createSequence($interface, $this->getContext()),
      $label);
  }

  /**
   * @param int $index
   * @param string $interface
   * @param string|null $label
   *
   * @return static
   */
  public function withParam_IfaceOrNull($index, $interface, $label = NULL) {
    return $this->withParamSchema(
      $index,
      CfSchema_IfaceWithContext::createOptional($interface, $this->getContext()),
      $label);
  }

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback() {
    return $this->callbackReflection;
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  public function getExplicitParamSchemas() {
    return $this->explicitSchemas;
  }

  /**
   * @return string[]
   */
  public function getExplicitParamLabels() {
    return $this->explicitLabels;
  }

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext() {
    return $this->context;
  }
}
