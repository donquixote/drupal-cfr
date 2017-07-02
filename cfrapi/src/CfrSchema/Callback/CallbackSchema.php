<?php

namespace Drupal\cfrapi\CfrSchema\Callback;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;
use Drupal\cfrapi\CfrSchema\Iface\IfaceSchema;
use Drupal\cfrapi\Context\CfrContextInterface;

class CallbackSchema implements CallbackSchemaInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @var \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[]
   */
  private $explicitSchemas = [];

  /**
   * @var string[]
   */
  private $explicitLabels = [];

  /**
   * @var \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  private $context;

  /**
   * @param string $class
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrapi\CfrSchema\Callback\CallbackSchema
   */
  public static function createFromClass($class, CfrContextInterface $context = NULL) {

    return new self(
      CallbackReflection_ClassConstruction::create($class),
      $context);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return self
   */
  public static function createFromClassStaticMethod($class, $methodName, CfrContextInterface $context = NULL) {

    return new self(
      CallbackReflection_StaticMethod::create(
        $class,
        $methodName),
      $context);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   */
  public function __construct(
    CallbackReflectionInterface $callbackReflection,
    CfrContextInterface $context = NULL
  ) {
    $this->callbackReflection = $callbackReflection;
    $this->context = $context;
  }

  /**
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   *
   * @return static
   */
  public function withContext(CfrContextInterface $context = NULL) {
    $clone = clone $this;
    $clone->context = $context;
    return $clone;
  }

  /**
   * @param int $index
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $schema
   * @param string|null $label
   *
   * @return static
   */
  public function withParamSchema($index, CfrSchemaInterface $schema, $label = NULL) {
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
      new IfaceSchema($interface, $this->context),
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
      IfaceSchema::createSequence($interface, $this->getContext()),
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
      IfaceSchema::createOptional($interface, $this->getContext()),
      $label);
  }

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback() {
    return $this->callbackReflection;
  }

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[]
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
   * @return \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  public function getContext() {
    return $this->context;
  }
}
