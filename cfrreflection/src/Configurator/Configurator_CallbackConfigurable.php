<?php

namespace Drupal\cfrreflection\Configurator;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ObjectMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\Configurator\Group\Configurator_GroupBase;
use Drupal\cfrreflection\Util\CfrReflectionUtil;

class Configurator_CallbackConfigurable extends Configurator_GroupBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $className
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createFromClassName($className, array $paramConfigurators, array $labels) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($className);
    return new self($callback, $paramConfigurators, $labels);
  }

  /**
   * @param string $className
   * @param string $methodName
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createFromClassStaticMethod($className, $methodName, array $paramConfigurators, array $labels) {
    $callback = CallbackReflection_StaticMethod::create($className, $methodName);
    return new self($callback, $paramConfigurators, $labels);
  }

  /**
   * @param object $object
   * @param string $methodName
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createFromObjectMethod($object, $methodName, array $paramConfigurators, array $labels) {
    $callback = CallbackReflection_ObjectMethod::create($object, $methodName);
    return new self($callback, $paramConfigurators, $labels);
  }

  /**
   * @param callable $callable
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createFromCallable($callable, array $paramConfigurators, array $labels) {
    $callback = CallbackUtil::callableGetCallback($callable);
    return new self($callback, $paramConfigurators, $labels);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface[] $paramConfigurators
   * @param string[] $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createFromCallback(CallbackReflectionInterface $callback, array $paramConfigurators, array $labels) {
    return new self($callback, $paramConfigurators, $labels);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface[] $paramConfigurators
   * @param string[] $labels
   */
  public function __construct(CallbackReflectionInterface $callback, array $paramConfigurators = [], array $labels = []) {
    $this->callback = $callback;
    foreach ($paramConfigurators as $k => $paramConfigurator) {
      $paramLabel = isset($labels[$k]) ? $labels[$k] : $k;
      $this->keySetConfigurator($k, $paramConfigurator, $paramLabel);
    }
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf) {

    $args = parent::confGetValue($conf);

    if (!is_array($args)) {
      return new BrokenValue($this, get_defined_vars(), 'Non-array callback arguments.');
    }

    if (NULL !== $brokenValue = CfrReflectionUtil::callbackArgsInvalid($this->callback, $args)) {
      return $brokenValue;
    }

    try {
      return $this->callback->invokeArgs($args);
    }
    catch (\Exception $e) {
      return new BrokenValue($this, get_defined_vars(), 'Exception during callback.');
    }
  }
}
