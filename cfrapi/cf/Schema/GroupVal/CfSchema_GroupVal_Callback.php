<?php

namespace Donquixote\Cf\Schema\GroupVal;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\CodegenHelper\CodegenHelper;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\Cf\Schema\Group\CfSchema_Group;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;

class CfSchema_GroupVal_Callback extends CfSchema_GroupValBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callbackReflection;

  /**
   * @param string $class
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return self
   */
  public static function createFromClass($class, array $schemas, array $labels) {

    return self::create(
      CallbackReflection_ClassConstruction::createFromClassName(
        $class),
      $schemas,
      $labels);
  }

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return self
   */
  public static function createFromClassStaticMethod($class, $methodName, array $schemas, array $labels) {

    return self::create(
      CallbackReflection_StaticMethod::create(
        $class,
        $methodName),
      $schemas,
      $labels);
  }

  /**
   * @param callable $callable
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return self
   */
  public static function createFromCallable($callable, array $schemas, array $labels) {

    return self::create(
      CallbackUtil::callableGetCallback($callable),
      $schemas,
      $labels);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return self
   */
  public static function create(
    CallbackReflectionInterface $callbackReflection,
    array $schemas,
    array $labels
  ) {

    return new self(
      new CfSchema_Group($schemas, $labels),
      $callbackReflection);
  }

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $groupSchema
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callbackReflection
   */
  public function __construct(
    CfSchema_GroupInterface $groupSchema,
    CallbackReflectionInterface $callbackReflection
  ) {
    $this->callbackReflection = $callbackReflection;
    parent::__construct($groupSchema);
  }

  /**
   * @param mixed[] $values
   *   Format: $[$groupValItemKey] = $groupValItemValue
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
