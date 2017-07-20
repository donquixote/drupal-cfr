<?php

namespace Drupal\cfrfamily\IdToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;
use Donquixote\Cf\DefinitionMap\DefinitionMapInterface;
use Drupal\cfrfamily\DefinitionToConfigurator\DefinitionToConfiguratorInterface;

/**
 * @deprecated in favour of:
 * @see \Drupal\cfrfamily\IdToConfigurator\IdToConfigurator_ViaDefinition
 */
class IdToConfigurator_FromDefinitionMap implements IdToConfiguratorInterface {

  /**
   * @var \Donquixote\Cf\DefinitionMap\DefinitionMapInterface
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
   * @param \Donquixote\Cf\DefinitionMap\DefinitionMapInterface $definitionMap
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

    try {
      return $this->definitionToConfigurator->definitionGetConfigurator($definition, $this->context);
    }
    catch (ConfiguratorCreationException $e) {
      // @todo Report this in watchdog?
      return NULL;
    }
  }
}
