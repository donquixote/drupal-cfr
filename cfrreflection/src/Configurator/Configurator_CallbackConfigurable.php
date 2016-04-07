<?php

namespace Drupal\cfrreflection\Configurator;

use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ObjectMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Drupal\cfrapi\BrokenValue\BrokenValue;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Configurator\Group\GroupConfiguratorInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Configurator\Group\Configurator_Group;
use Drupal\cfrreflection\Util\CfrReflectionUtil;

class Configurator_CallbackConfigurable implements ConfiguratorInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @var \Drupal\cfrapi\Configurator\Group\GroupConfiguratorInterface
   */
  private $argsConfigurator;

  /**
   * @param string $className
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createFromClassName($className, array $paramConfigurators, array $labels) {
    $callback = CallbackReflection_ClassConstruction::createFromClassName($className);
    return self::createFromCallback($callback, $paramConfigurators, $labels);
  }

  /**
   * @param string $className
   * @param string $methodName
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createFromClassStaticMethod($className, $methodName, array $paramConfigurators, array $labels) {
    $callback = CallbackReflection_StaticMethod::create($className, $methodName);
    return self::createFromCallback($callback, $paramConfigurators, $labels);
  }

  /**
   * @param object $object
   * @param string $methodName
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createFromObjectMethod($object, $methodName, array $paramConfigurators, array $labels) {
    $callback = CallbackReflection_ObjectMethod::create($object, $methodName);
    return self::createFromCallback($callback, $paramConfigurators, $labels);
  }

  /**
   * @param callable $callable
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createFromCallable($callable, array $paramConfigurators, array $labels) {
    $callback = CallbackUtil::callableGetCallback($callable);
    return self::createFromCallback($callback, $paramConfigurators, $labels);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param array $paramConfigurators
   * @param array $labels
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  static function createFromCallback(CallbackReflectionInterface $callback, array $paramConfigurators, array $labels) {
    if (empty($paramConfigurators)) {
      return new Configurator_CallbackSimple($callback);
    }
    $paramsConfigurator = Configurator_Group::createFromConfigurators($paramConfigurators, $labels);
    return new self($callback, $paramsConfigurator);
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Drupal\cfrapi\Configurator\Group\GroupConfiguratorInterface $argsConfigurator
   */
  function __construct(CallbackReflectionInterface $callback, GroupConfiguratorInterface $argsConfigurator) {
    $this->callback = $callback;
    $this->argsConfigurator = $argsConfigurator;
  }

  /**
   * @param array $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetForm($conf, $label) {
    return $this->argsConfigurator->confGetForm($conf, $label);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->argsConfigurator->confGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  function confGetValue($conf) {
    $args = $this->argsConfigurator->confGetValue($conf);
    if (!is_array($args)) {
      return new BrokenValue($this, get_defined_vars(), 'Non-array callback arguments.');
    }
    if (NULL !== $brokenValue = CfrReflectionUtil::callbackArgsInvalid($this->callback, $args)) {
      return $brokenValue;
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
