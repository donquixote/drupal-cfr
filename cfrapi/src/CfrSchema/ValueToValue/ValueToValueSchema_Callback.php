<?php

namespace Drupal\cfrapi\CfrSchema\ValueToValue;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

class ValueToValueSchema_Callback extends ValueToValueSchemaBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $class
   * @param string $methodName
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $decorated
   *
   * @return \Drupal\cfrapi\CfrSchema\ValueToValue\ValueToValueSchemaInterface
   */
  public static function createFromClassStaticMethod($class, $methodName, CfrSchemaInterface $decorated) {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return new self($decorated, $callback);
  }

  /**
   * @param string $class
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $decorated
   *
   * @return \Drupal\cfrapi\CfrSchema\ValueToValue\ValueToValueSchemaInterface
   */
  public static function createFromClass($class, CfrSchemaInterface $decorated) {
    $callback = CallbackReflection_ClassConstruction::create($class);
    return new self($decorated, $callback);
  }

  /**
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface $decorated
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  public function __construct(CfrSchemaInterface $decorated, CallbackReflectionInterface $callback) {
    parent::__construct($decorated);
    $this->callback = $callback;
  }

  /**
   * @param mixed $value
   *
   * @return mixed
   */
  public function valueGetValue($value) {
    return $this->callback->invokeArgs([$value]);
  }


  /**
   * @param string $php
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function phpGetPhp($php, CfrCodegenHelperInterface $helper) {
    return $this->callback->argsPhpGetPhp([$php], $helper);
  }
}
