<?php

namespace Drupal\cfrreflection\CfrGen\ArgDefToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfiguratorInterface;
use Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface;
use Drupal\cfrreflection\ValueToCallback\ValueToCallbackInterface;

class ArgDefToConfigurator_Callback implements ArgDefToConfiguratorInterface {

  /**
   * @var \Drupal\cfrreflection\ValueToCallback\ValueToCallbackInterface
   */
  private $valueToCallback;

  /**
   * @var string
   */
  private $argsKey;

  /**
   * @var \Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface
   */
  private $callbackToConfigurator;

  /**
   * @param \Drupal\cfrreflection\ValueToCallback\ValueToCallbackInterface $valueToCallback
   * @param string $argsKey
   * @param \Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface $callbackToConfigurator
   */
  public function __construct(ValueToCallbackInterface $valueToCallback, $argsKey, CallbackToConfiguratorInterface $callbackToConfigurator) {
    $this->valueToCallback = $valueToCallback;
    $this->argsKey = $argsKey;
    $this->callbackToConfigurator = $callbackToConfigurator;
  }

  /**
   * @param mixed $arg
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  public function argDefinitionGetConfigurator($arg, array $definition, CfrContextInterface $context = NULL) {

    $factory = $this->valueToCallback->valueGetCallback($arg);

    if (NULL === $factory) {
      return NULL;
    }

    if (!empty($definition[$this->argsKey])) {
      $namedArgs = $definition[$this->argsKey];
      $factory = new CallbackReflection_BoundParameters($factory, $namedArgs);
    }

    return $this->callbackToConfigurator->callbackGetConfigurator($factory, $context);
  }

}
