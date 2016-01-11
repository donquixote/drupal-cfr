<?php

namespace Drupal\cfrfamily\ConfiguratorMap;

use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface;

class ConfiguratorMap_FromDefinitionMap implements ConfiguratorMapInterface {

  /**
   * @var \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   */
  private $definitionToConfigurator;

  /**
   * @var \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  private $context;

  /**
   * @param \Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface $definitionToConfigurator
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   */
  function __construct(
    DefinitionMapInterface $definitionMap,
    DefinitionToConfiguratorInterface $definitionToConfigurator,
    CfrContextInterface $context = NULL
  ) {
    $this->definitionMap = $definitionMap;
    $this->definitionToConfigurator = $definitionToConfigurator;
    $this->context = $context;
  }

  /**
   * @param string|int $id
   *
   * @return null|\Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function idGetConfigurator($id) {
    $definition = $this->definitionMap->idGetDefinition($id);
    if (NULL === $definition) {
      return new BrokenConfigurator($this, get_defined_vars(), 'No definition found.');
    }
    return $this->definitionToConfigurator->definitionGetConfigurator($definition, $this->context);
  }
}
