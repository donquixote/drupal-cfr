<?php

namespace Drupal\cfrfamily\DefinitionToConfigurator;

use Drupal\cfrapi\Configurator\Broken\BrokenConfiguratorInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfiguratorInterface;

class DefinitionToConfigurator_Mappers implements DefinitionToConfiguratorInterface {

  /**
   * @var \Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfiguratorInterface[]
   */
  private $mappers;

  /**
   * @param string $key
   * @param \Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfiguratorInterface $mapper
   */
  public function keySetMapper($key, ArgDefToConfiguratorInterface $mapper) {
    $this->mappers[$key] = $mapper;
  }

  /**
   * @param array $definition
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  public function definitionGetConfigurator(array $definition, CfrContextInterface $context = NULL) {

    foreach ($this->mappers as $key => $mapper) {
      if (isset($definition[$key])) {
        $candidate = $mapper->argDefinitionGetConfigurator($definition[$key], $definition, $context);
        if ($candidate instanceof ConfiguratorInterface) {
          if ($candidate instanceof BrokenConfiguratorInterface) {
            return NULL;
          }
          return $candidate;
        }
        elseif (NULL !== $candidate) {
          return NULL;
        }
      }
    }

    return NULL;
  }
}
