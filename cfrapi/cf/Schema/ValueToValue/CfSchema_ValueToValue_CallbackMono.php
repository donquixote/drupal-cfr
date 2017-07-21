<?php

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\UtilBase;
use Donquixote\Cf\V2V\Value\V2V_Value_CallbackMono;

final class CfSchema_ValueToValue_CallbackMono extends UtilBase {

  /**
   * @param string $class
   * @param string $methodName
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   *
   * @return self
   */
  public static function createFromClassStaticMethod($class, $methodName, CfSchemaInterface $decorated) {
    $callback = CallbackReflection_StaticMethod::create($class, $methodName);
    return self::create($decorated, $callback);
  }

  /**
   * @param string $class
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decorated
   *
   * @return self
   */
  public static function createFromClass($class, CfSchemaInterface $decorated) {
    $callback = CallbackReflection_ClassConstruction::create($class);
    return self::create($decorated, $callback);
  }

  public static function create(CfSchemaInterface $decorated, CallbackReflectionInterface $callback) {
    return new CfSchema_ValueToValue(
      $decorated,
      new V2V_Value_CallbackMono($callback));
  }
}
