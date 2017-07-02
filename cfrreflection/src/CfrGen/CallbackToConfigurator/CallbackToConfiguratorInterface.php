<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Context\CfrContextInterface;

interface CallbackToConfiguratorInterface {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrfamily\Exception\DefinitionToConfiguratorException
   */
  public function callbackGetConfigurator(CallbackReflectionInterface $callback, CfrContextInterface $context = NULL);

}
