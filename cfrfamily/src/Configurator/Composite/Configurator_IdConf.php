<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\Legend\LegendInterface;
use Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface;

class Configurator_IdConf extends Configurator_IdConfBase {

  /**
   * @var \Drupal\cfrapi\Legend\LegendInterface
   */
  private $legend;

  /**
   * @var \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   */
  private $idToConfigurator;

  /**
   * @param \Drupal\cfrapi\Legend\LegendInterface $legend
   * @param \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
   */
  public function __construct(LegendInterface $legend, IdToConfiguratorInterface $idToConfigurator) {
    $this->legend = $legend;
    $this->idToConfigurator = $idToConfigurator;
    parent::__construct(TRUE);
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {
    return $this->legend->getSelectOptions();
  }

  /**
   * @param string $id
   *
   * @return string
   */
  protected function idGetLabel($id) {
    return $this->legend->idGetLabel($id);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  protected function idGetConfigurator($id) {
    return $this->idToConfigurator->idGetConfigurator($id);
  }
}
