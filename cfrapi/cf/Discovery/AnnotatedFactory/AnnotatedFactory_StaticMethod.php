<?php

namespace Donquixote\Cf\Discovery\AnnotatedFactory;

use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\Cf\Util\ReflectionUtil;

class AnnotatedFactory_StaticMethod implements AnnotatedFactoryInterface {

  /**
   * @var \ReflectionMethod
   */
  private $method;

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @var string[]
   */
  private $returnTypeNames;

  /**
   * @param \ReflectionMethod $method
   *
   * @return self
   */
  public static function createFromStaticMethod(\ReflectionMethod $method) {

    $returnTypeNames = ReflectionUtil::functionGetReturnTypeNames($method);

    return new self(
      $method,
      $returnTypeNames);
  }

  /**
   * @param \ReflectionMethod $method
   * @param string[] $returnTypeNames
   */
  public function __construct(
    \ReflectionMethod $method,
    array $returnTypeNames
  ) {
    $this->method = $method;
    $this->callback = new CallbackReflection_StaticMethod($method);
    $this->returnTypeNames = $returnTypeNames;
  }

  /**
   * @return \ReflectionMethod
   */
  public function getReflectionMethod() {
    return $this->method;
  }

  /**
   * @param string $prefix
   *
   * @return array|null
   */
  public function createDefinition($prefix) {
    return [
      $prefix . '_factory' => $this->method->getDeclaringClass()->getName()
        . '::' .  $this->method->getName(),
    ];
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
    return $this->method->getDocComment();
  }

  /**
   * @return string[]
   */
  public function getReturnTypeNames() {
    return $this->returnTypeNames;
  }

}
