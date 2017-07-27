<?php

namespace Donquixote\Cf\Discovery\AnnotatedFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;

class AnnotatedFactory_Class implements AnnotatedFactoryInterface {

  /**
   * @var \ReflectionClass
   */
  private $reflectionClass;

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction
   */
  private $callback;

  /**
   * @param \ReflectionClass $reflectionClass
   */
  public function __construct(\ReflectionClass $reflectionClass) {
    $this->reflectionClass = $reflectionClass;
    $this->callback = new CallbackReflection_ClassConstruction($reflectionClass);
  }

  /**
   * @return \ReflectionClass
   */
  public function getReflectionClass() {
    return $this->reflectionClass;
  }

  /**
   * @param string $prefix
   *
   * @return array
   */
  public function createDefinition($prefix) {
    return [$prefix . '_class' => $this->reflectionClass->getName()];
  }

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback() {
    return $this->callback;
  }

  /**
   * @return string
   */
  public function getDocComment() {
    return $this->reflectionClass->getDocComment();
  }

  /**
   * @return string[]
   */
  public function getReturnTypeNames() {
    return [$this->reflectionClass->getName()];
  }

}
