<?php

namespace Drupal\cfrfamily\DefinitionToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;

interface DefinitionToConfiguratorInterface {

  /**
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function definitionGetConfigurator(array $definition, CfrContextInterface $context = NULL);

}
