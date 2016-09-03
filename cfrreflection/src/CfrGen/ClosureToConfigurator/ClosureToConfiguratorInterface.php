<?php

namespace Drupal\cfrreflection\CfrGen\ClosureToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;

interface ClosureToConfiguratorInterface {

  /**
   * @param \Closure $closure
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function closureGetConfigurator(\Closure $closure, CfrContextInterface $context);

}
