<?php

namespace Drupal\cfrreflection\ValueToValue;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\ValueToValue\ValueToValueInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ObjectMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;

class ValueToValue_Callback implements ValueToValueInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $className
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createFromClassName($className) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($className);
    return new self($callback);
  }

  /**
   * @param string $className
   * @param string $methodName
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createFromClassStaticMethod($className, $methodName) {
    $callback = CallbackReflection_StaticMethod::create($className, $methodName);
    return new self($callback);
  }

  /**
   * @param object $object
   * @param string $methodName
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createFromObjectMethod($object, $methodName) {
    $callback = CallbackReflection_ObjectMethod::create($object, $methodName);
    return new self($callback);
  }

  /**
   * @param string $callable
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createFromCallable($callable) {
    $callback = CallbackUtil::callableGetCallback($callable);
    return new self($callback);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  function __construct(CallbackReflectionInterface $callback) {
    $this->callback = $callback;
  }

  /**
   * Processes or replaces the value.
   *
   * @param mixed $args
   *
   * @return mixed
   */
  function valueGetValue($args) {
    if (!is_array($args)) {
      return new BrokenValue($this, get_defined_vars(), 'Non-array callback arguments.');
    }
    // @todo Validate arguments.
    try {
      return $this->callback->invokeArgs($args);
    }
    catch (\Exception $e) {
      return new BrokenValue($this, get_defined_vars(), 'Exception during callback.');
    }
  }

}
