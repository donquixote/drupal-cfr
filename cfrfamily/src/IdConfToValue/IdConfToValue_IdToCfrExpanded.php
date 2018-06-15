<?php

namespace Drupal\cfrfamily\IdConfToValue;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\Exception\ConfToValueException;
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
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function idConfGetValue($id, $conf) {

    if (NULL === $id) {
      throw new ConfToValueException("Required id missing.");
    }

    if (NULL !== $configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      return $configurator->confGetValue($conf);
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
      return $configurator->idConfGetValue($subId, $conf);
    }

    throw new ConfToValueException("Unknown id '$id'.");
  }

  /**
   * @param string|int $id
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  function idConfGetPhp($id, $conf, CfrCodegenHelperInterface $helper) {

    if (NULL === $id) {
      return $helper->incompatibleConfiguration($id, "Required id missing.");
    }

    if (NULL !== $configurator = $this->idToConfigurator->idGetConfigurator($id)) {
      return $configurator->confGetPhp($conf, $helper);
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
      // @todo This is not 100% consistent with confGetValue().
      return $configurator->idConfGetPhp($subId, $conf, $helper);
    }

    return $helper->incompatibleConfiguration($id, "Unknown id.");
  }
}
