<?php

namespace Donquixote\Cf\Schema\Group;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\CallbackReflection\Util\CallbackUtil;

class GroupSchema_Callback extends CfSchema_GroupBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @param string $class
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  public static function createFromClass($class, array $schemas, array $labels) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($class);
    return new self($callback, $schemas, $labels);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  public static function createFromClassStaticMethod($class, $methodName, array $schemas, array $labels) {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return new self($callback, $schemas, $labels);
  }

  /**
   * @param callable $callable
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Donquixote\Cf\Schema\Group\GroupSchema_Callback
   */
  public static function createFromCallable($callable, array $schemas, array $labels) {
    $callback = CallbackUtil::callableGetCallback($callable);
    return new self($callback, $schemas, $labels);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
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
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp) {
    // @todo Does the helper need to be passed into this method?
    $helper = new CodegenHelper();
    return $this->callbackReflection->argsPhpGetPhp($itemsPhp, $helper);
  }
}
