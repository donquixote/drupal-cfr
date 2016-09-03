<?php

namespace Drupal\cfrfamily\IdConfToValue;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\Configurator\Broken\BrokenConfiguratorInterface;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class IdConfToValue_IdToCfrExpanded implements IdConfToValueInterface {

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
   */
  public function idConfGetValue($id, $conf) {

    if (NULL === $id) {
      return new BrokenValue($this, get_defined_vars(), 'Required.');
    }

    if (NULL !== $configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      if (!$configurator instanceof BrokenConfiguratorInterface) {
        return $configurator->confGetValue($conf);
      }
    }

    $pos = 0;
    while (FALSE !== $pos = strpos($id, '/', $pos + 1)) {
      $k = substr($id, 0, $pos);
      if (NULL === $configurator = $this->idToConfigurator->idGetConfigurator($k)) {
        continue;
      }
      if (!$configurator instanceof InlineableConfiguratorInterface) {
        continue;
      }
      $subId = substr($id, $pos + 1);
      $candidate = $configurator->idConfGetValue($subId, $conf);
      if (!$candidate instanceof BrokenValueInterface) {
        return $candidate;
      }
    }

    return new BrokenValue($this, get_defined_vars(), 'Unknown id.');
  }
}
