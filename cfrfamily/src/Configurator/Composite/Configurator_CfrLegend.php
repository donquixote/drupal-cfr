<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrfamily\CfrLegend\CfrLegendInterface;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface;
use Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface;

class Configurator_CfrLegend extends Configurator_IdConfGrandBase implements InlineableConfiguratorInterface {

  /**
   * @var \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  private $legend;

  /**
   * @var \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface
   */
  private $idConfToValue;

  /**
   * @param bool $required
   * @param \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $legend
   * @param \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface $idConfToValue
   */
  public function __construct(
    $required,
    CfrLegendInterface $legend,
    IdConfToValueInterface $idConfToValue
  ) {
    parent::__construct($required);
    $this->legend = $legend;
    $this->idConfToValue = $idConfToValue;
  }

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {

    $options = [];
    $groups = [];
    foreach ($this->legend->getLegendItems() as $id => $item) {
      $itemLabel = $item->getLabel();
      if (!$item->isOptionless()) {
        $itemLabel .= '…';
      }
      if (NULL === $groupLabel = $item->getGroupLabel()) {
        $options[$id] = $itemLabel;
      }
      else {
        $groups[$groupLabel][$id] = $itemLabel;
      }
    }

    asort($options);
    ksort($groups);
    foreach ($groups as &$group) {
      asort($group);
    }

    return $options + $groups;
  }

  /**
   * @param string $id
   *
   * @return string
   */
  protected function idGetLabel($id) {

    if (NULL === $item = $this->legend->idGetLegendItem($id)) {
      return $id;
    }

    return $item->getLabel();
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return array|null
   */
  protected function idConfGetOptionsForm($id, $optionsConf) {

    if (NULL === $item = $this->legend->idGetLegendItem($id)) {
      return NULL;
    }

    return $item->confGetForm($optionsConf, NULL);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return string|null
   */
  protected function idConfGetSummary($id, $optionsConf, SummaryBuilderInterface $summaryBuilder) {

    if (NULL === $legendItem = $this->legend->idGetLegendItem($id)) {
      return '- ' . t('Unknown') . ' -';
    }

    $idLabel = $legendItem->getLabel();

    return $summaryBuilder->idConf($idLabel, $legendItem, $optionsConf);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  public function idConfGetValue($id, $optionsConf) {
    return $this->idConfToValue->idConfGetValue($id, $optionsConf);
  }

  /**
   * @param string $id
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function idConfGetPhp($id, $conf, CfrCodegenHelperInterface $helper) {
    return $this->idConfToValue->idConfGetPhp($id, $conf, $helper);
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface|null
   */
  public function getCfrLegend() {
    return $this->legend;
  }
}
