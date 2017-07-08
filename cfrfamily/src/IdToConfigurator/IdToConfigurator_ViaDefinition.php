<?php

namespace Drupal\cfrfamily\IdToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface;
use Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface;

class IdToConfigurator_ViaDefinition implements IdToConfiguratorInterface {

  /**
   * @var \Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @var \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface
   */
  private $definitionToConfigurator;

  /**
   * @var \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  private $context;

  /**
   * @param \Drupal\cfrfamily\IdToDefinition\IdToDefinitionInterface $definitionMap
   * @param \Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface $definitionToConfigurator
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   */
  public function __construct(
    IdToDefinitionInterface $definitionMap,
    DefinitionToConfiguratorInterface $definitionToConfigurator,
    CfrContextInterface $context = NULL
  ) {
    $this->idToDefinition = $definitionMap;
    $this->definitionToConfigurator = $definitionToConfigurator;
    $this->context = $context;
  }

  /**
   * @param string|int $id
   *
   * @return null|\Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function idGetConfigurator($id) {

    $definition = $this->idToDefinition->idGetDefinition($id);

    if (NULL === $definition) {
      return NULL;
    }

    try {
      return $this->definitionToConfigurator->definitionGetConfigurator($definition, $this->context);
    }
    catch (ConfiguratorCreationException $e) {
      // @todo Report this in watchdog?
      return NULL;
    }
  }
}
