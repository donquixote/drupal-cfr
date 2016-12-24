<?php

namespace Drupal\cfrfamily\IdToConfigurator;

use Drupal\cfrapi\Configurator\Broken\BrokenConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface;

/**
 * @deprecated in favour of:
 * @see \Drupal\cfrfamily\IdToConfigurator\IdToConfigurator_ViaDefinition
 */
class IdToConfigurator_FromDefinitionMap implements IdToConfiguratorInterface {

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
  public function __construct(
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
  public function idGetConfigurator($id) {

    $definition = $this->definitionMap->idGetDefinition($id);

    if (NULL === $definition) {
      return NULL;
    }

    $configurator = $this->definitionToConfigurator->definitionGetConfigurator($definition, $this->context);

    if (NULL === $configurator) {
      return NULL;
    }

    if ($configurator instanceof BrokenConfiguratorInterface) {
      return NULL;
    }

    return $configurator;
  }
}
