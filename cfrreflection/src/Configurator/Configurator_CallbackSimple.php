<?php

namespace Drupal\cfrreflection\Configurator;

use Donquixote\CallbackReflection\ArgsPhpToPhp\ArgsPhpToPhpInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator;
use Drupal\cfrapi\Configurator\Unconfigurable\Configurator_OptionlessBase;
use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;
use Drupal\cfrapi\Exception\PhpGenerationNotSupportedException;

class Configurator_CallbackSimple extends Configurator_OptionlessBase implements ConfToPhpInterface {

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
    return new Configurator_CallbackSimple($callback);
  }

  /**
   * @param mixed|callable $callable
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createFromCallable($callable) {
    $callback = CallbackUtil::callableGetCallback($callable);
    if (NULL === $callback) {
      return new BrokenConfigurator(NULL, get_defined_vars(), 'Not a valid callback.');
    }
    return new Configurator_CallbackSimple($callback);
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
   */
  public function confGetValue($conf) {
    try {
      return $this->callback->invokeArgs([]);
    }
    catch (\Exception $e) {
      return new BrokenValue($this, get_defined_vars(), 'Exception during callback.');
    }
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetPhp($conf) {

    $callback = $this->callback;
    if (!$callback instanceof ArgsPhpToPhpInterface) {
      $class = get_class($callback);
      throw new PhpGenerationNotSupportedException("\$this->callback of class '$class' does not support code generation.");
    }

    // @todo Cast any exceptions from the callback.
    return $callback->argsPhpGetPhp(array());
  }
}
