<?php

namespace Drupal\cfrreflection\ValueToValue;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ObjectMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Drupal\cfrapi\ValueToValue\ValueToValueInterface;
use Drupal\cfrreflection\Util\CfrReflectionUtil;

class ValueToValue_Callback implements ValueToValueInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $className
   *
   * @return \Drupal\cfrapi\ValueToValue\ValueToValueInterface
   */
  public static function createFromClassName($className) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($className);
    return new self($callback);
  }

  /**
   * @param string $className
   * @param string $methodName
   *
   * @return \Drupal\cfrapi\ValueToValue\ValueToValueInterface
   */
  public static function createFromClassStaticMethod($className, $methodName) {
    $callback = CallbackReflection_StaticMethod::create($className, $methodName);
    return new self($callback);
  }

  /**
   * @param object $object
   * @param string $methodName
   *
   * @return \Drupal\cfrapi\ValueToValue\ValueToValueInterface
   */
  public static function createFromObjectMethod($object, $methodName) {
    $callback = CallbackReflection_ObjectMethod::create($object, $methodName);
    return new self($callback);
  }

  /**
   * @param string $callable
   *
   * @return \Drupal\cfrapi\ValueToValue\ValueToValueInterface
   */
  public static function createFromCallable($callable) {
    $callback = CallbackUtil::callableGetCallback($callable);
    return new self($callback);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  public function __construct(CallbackReflectionInterface $callback) {
    $this->callback = $callback;
  }

  /**
   * Processes or replaces the value.
   *
   * @param mixed $args
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function valueGetValue($args) {
    return CfrReflectionUtil::callbackValidateAndInvoke($this->callback, $args);
  }

}
