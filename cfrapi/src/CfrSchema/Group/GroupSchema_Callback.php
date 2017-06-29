<?php

namespace Drupal\cfrapi\CfrSchema\Group;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class GroupSchema_Callback extends GroupSchemaBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @param string $class
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Drupal\cfrapi\CfrSchema\Group\GroupSchemaInterface
   */
  public static function createFromClass($class, array $schemas, array $labels) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($class);
    return new self($callback, $schemas, $labels);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Drupal\cfrapi\CfrSchema\Group\GroupSchemaInterface
   */
  public static function createFromClassStaticMethod($class, $methodName, array $schemas, array $labels) {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return new self($callback, $schemas, $labels);
  }

  /**
   * @param callable $callable
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Drupal\cfrapi\CfrSchema\Group\GroupSchema_Callback
   */
  public static function createFromCallable($callable, array $schemas, array $labels) {
    $callback = CallbackUtil::callableGetCallback($callable);
    return new self($callback, $schemas, $labels);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Drupal\cfrapi\CfrSchema\CfrSchemaInterface[] $schemas
   * @param string[] $labels
   */
  public function __construct(
    CallbackReflectionInterface $callbackReflection,
    array $schemas,
    array $labels
  ) {
    $this->callbackReflection = $callbackReflection;
    parent::__construct($schemas, $labels);
  }

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed
   */
  public function valuesGetValue(array $values) {
    return $this->callbackReflection->invokeArgs($values);
  }

  /**
   * @param string[] $itemsPhp
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp, CfrCodegenHelperInterface $helper) {
    return $this->callbackReflection->argsPhpGetPhp($itemsPhp, $helper);
  }
}
