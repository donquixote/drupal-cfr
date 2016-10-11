<?php

namespace Drupal\cfrfamily\IdConfToValue;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class IdConfToValue_IdToConfigurator implements IdConfToValueInterface {

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   */
  public function __construct(IdToConfiguratorInterface $idToConfigurator) {
    $this->idToConfigurator = $idToConfigurator;
  }

  /**
   * @param string|null $id
   * @param mixed $conf
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function idConfGetValue($id, $conf) {

    if (NULL === $id) {
      throw new InvalidConfigurationException("Required id is empty.");
    }

    if (NULL === $configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      throw new InvalidConfigurationException("Unknown id '$id'.");
    }

    return $configurator->confGetValue($conf);
  }

  /**
   * @param string|int $id
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  function idConfGetPhp($id, $conf, CfrCodegenHelperInterface $helper) {

    if (NULL === $id) {
      return $helper->incompatibleConfiguration($id, "Required id missing.");
    }

    if (NULL === $configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      return $helper->incompatibleConfiguration($id, "Unknown id.");
    }

    return $configurator->confGetPhp($conf, $helper);
  }
}
