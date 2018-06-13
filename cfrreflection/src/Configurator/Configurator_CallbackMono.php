<?php

namespace Drupal\cfrreflection\Configurator;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Configurator\Configurator_DecoratorBase;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrreflection\Util\CfrReflectionUtil;

class Configurator_CallbackMono extends Configurator_DecoratorBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $className
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $argConfigurator
   *
   * @return \Drupal\cfrreflection\Configurator\Configurator_CallbackMono
   */
  public static function createFromClassName($className, ConfiguratorInterface $argConfigurator) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($className);
    return new self($callback, $argConfigurator);
  }

  /**
   * @param string $className
   * @param string $methodName
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $argConfigurator
   *
   * @return \Drupal\cfrreflection\Configurator\Configurator_CallbackMono
   */
  public static function createFromClassStaticMethod($className, $methodName, ConfiguratorInterface $argConfigurator) {
    $callback = CallbackReflection_StaticMethod::create($className, $methodName);
    return new self($callback, $argConfigurator);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $monoParamCallback
   *   Callback with exactly one parameter.
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $argConfigurator
   */
  public function __construct(CallbackReflectionInterface $monoParamCallback, ConfiguratorInterface $argConfigurator) {
    $this->callback = $monoParamCallback;
    parent::__construct($argConfigurator);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    $arg = parent::confGetValue($conf);
    return CfrReflectionUtil::callbackValidateAndInvoke($this->callback, [$arg]);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    $arg = parent::confGetPhp($conf, $helper);
    return $this->callback->argsPhpGetPhp([$arg], $helper);
  }
}
