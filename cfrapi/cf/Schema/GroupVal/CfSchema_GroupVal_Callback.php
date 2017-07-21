<?php

namespace Donquixote\Cf\Schema\GroupVal;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\Cf\Schema\Group\CfSchema_Group;
use Donquixote\Cf\Util\UtilBase;
use Donquixote\Cf\V2V\Group\V2V_Group_Callback;

final class CfSchema_GroupVal_Callback extends UtilBase {

  /**
   * @param string $class
   * @param \Donquixote\Cf\Schema\CfSchemaInterface[] $schemas
   * @param string[] $labels
   *
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
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
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
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
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
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
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public static function create(
    CallbackReflectionInterface $callbackReflection,
    array $schemas,
    array $labels
  ) {

    return new CfSchema_GroupVal(
      new CfSchema_Group($schemas, $labels),
      new V2V_Group_Callback($callbackReflection));
  }
}
