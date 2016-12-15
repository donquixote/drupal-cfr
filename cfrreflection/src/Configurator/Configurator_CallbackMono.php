<?php

namespace Drupal\cfrreflection\Configurator;

use Donquixote\CallbackReflection\ArgsPhpToPhp\ArgsPhpToPhpInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\Configurator\Configurator_DecoratorBase;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;

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
   */
  public function confGetValue($conf) {
    $arg = parent::confGetValue($conf);
    if ($arg instanceof BrokenValueInterface) {
      return $arg;
    }
    // @todo Validate $arg.
    try {
      return $this->callback->invokeArgs([$arg]);
    }
    catch (\Exception $e) {
      return new BrokenValue($this, get_defined_vars(), 'Exception during callback.');
    }
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CodegenHelperInterface $helper) {

    $arg = parent::confGetPhp($conf, $helper);

    if (!$this->callback instanceof ArgsPhpToPhpInterface) {
      return $helper->notSupported($this->callback, $conf, "Callback does not implement ArgsPhpToPhpInterface.");
    }

    return $this->callback->argsPhpGetPhp([$arg]);
  }
}
