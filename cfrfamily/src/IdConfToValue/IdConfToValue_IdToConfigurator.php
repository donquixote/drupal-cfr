<?php

namespace Drupal\cfrfamily\IdConfToValue;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;
use Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface;

class IdConfToValue_IdToConfigurator implements IdConfToValueInterface {

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @var \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|null
   */
  private $idValueToValue;

  /**
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   * @param \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface|NULL $idValueToValue
   */
  function __construct(IdToConfiguratorInterface $idToConfigurator, IdValueToValueInterface $idValueToValue = NULL) {
    $this->idToConfigurator = $idToConfigurator;
    $this->idValueToValue = $idValueToValue;
  }

  /**
   * @param string|null $id
   * @param mixed $conf
   *
   * @return mixed
   */
  function idConfGetValue($id, $conf) {

    if (NULL === $id) {
      return new BrokenValue($this, get_defined_vars(), 'Required.');
    }

    if (NULL === $configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      return new BrokenValue($this, get_defined_vars(), 'Unknown id.');
    }

    $value = $configurator->confGetValue($conf);

    if (NULL !== $this->idValueToValue && !$value instanceof BrokenValueInterface) {
      $value = $this->idValueToValue->idValueGetValue($id, $value);
    }

    return $value;
  }
}
