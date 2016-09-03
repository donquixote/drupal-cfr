<?php

namespace Drupal\cfrreflection\CfrGen\ClosureToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflection_Function;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface;

class ClosureToConfigurator_CallbackToConfigurator implements ClosureToConfiguratorInterface {

  /**
   * @var \Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface
   */
  private $callbackToConfigurator;

  /**
   * @param \Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface $callbackToConfigurator
   */
  public function __construct(CallbackToConfiguratorInterface $callbackToConfigurator) {
    $this->callbackToConfigurator = $callbackToConfigurator;
  }

  /**
   * @param \Closure $closure
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function closureGetConfigurator(\Closure $closure, CfrContextInterface $context) {
    $refl = new \ReflectionFunction($closure);
    $callback = new CallbackReflection_Function($refl);
    return $this->callbackToConfigurator->callbackGetConfigurator($callback);
  }
}
