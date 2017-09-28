<?php

namespace Drupal\cfrreflection\Configurator;

use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Configurator\Unconfigurable\Configurator_OptionlessBase;
use Drupal\cfrapi\Exception\InvalidConfigurationException;

class Configurator_CallbackSimple extends Configurator_OptionlessBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @param string $className
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createFromClassName($className) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($className);
    return new self($callback);
  }

  /**
   * @param mixed|callable $callable
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createFromCallable($callable) {
    $callback = CallbackUtil::callableGetCallback($callable);
    if (NULL === $callback) {
      return NULL;
    }
    return new self($callback);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   */
  public function __construct(CallbackReflectionInterface $callback) {
    $this->callback = $callback;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf) {

    try {
      return $this->callback->invokeArgs([]);
    }
    catch (\Exception $e) {
      throw new InvalidConfigurationException("Exception during callback.", NULL, $e);
    }
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
    return $this->callback->argsPhpGetPhp([], $helper);
  }
}
