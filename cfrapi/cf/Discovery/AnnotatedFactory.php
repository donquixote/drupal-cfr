<?php

namespace Donquixote\Cf\Discovery;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Util\ReflectionUtil;

class AnnotatedFactory {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @var string
   */
  private $docComment;

  /**
   * @var string[]
   */
  private $returnTypeNames;

  /**
   * @param \ReflectionClass $reflectionClass
   *
   * @return self
   */
  public static function createFromClass(\ReflectionClass $reflectionClass) {
    return new self(
      new CallbackReflection_ClassConstruction($reflectionClass),
      $reflectionClass->getDocComment(),
      [$reflectionClass->getName()]);
  }

  /**
   * @param \ReflectionMethod $method
   *
   * @return self
   */
  public static function createFromStaticMethod(\ReflectionMethod $method) {

    $returnTypeNames = ReflectionUtil::functionGetReturnTypeNames($method);

    return new self(
      new CallbackReflection_StaticMethod($method),
      $method->getDocComment(),
      $returnTypeNames);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param string $docComment
   * @param string[] $returnTypeNames
   */
  public function __construct(
    CallbackReflectionInterface $callback,
    $docComment,
    array $returnTypeNames
  ) {
    $this->callback = $callback;
    $this->docComment = $docComment;
    $this->returnTypeNames = $returnTypeNames;
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
    return $this->docComment;
  }

  /**
   * @return string[]
   */
  public function getReturnTypeNames() {
    return $this->returnTypeNames;
  }

}
