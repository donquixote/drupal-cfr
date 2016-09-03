<?php

namespace Drupal\cfrfamily\CfrLegendItem;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Util\ConfUtil;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;

class CfrLegendItem_Parent extends CfrLegendItem implements ParentLegendItemInterface {

  /**
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $legend;

  /**
   * @param string $label
   * @param string $groupLabel
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   */
  public function __construct($label, $groupLabel, ConfiguratorInterface $configurator, CfrLegendInterface $legend) {
    parent::__construct($label, $groupLabel, $configurator);
    $this->legend = $legend;
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface|null
   */
  public function getCfrLegend() {
    return $this->legend;
  }

  /**
   * @param mixed $conf
   *
   * @return array
   *   Format: array($id, $optionsConf)
   */
  public function confGetIdOptions($conf) {
    return ConfUtil::confGetIdOptions($conf);
  }
}
