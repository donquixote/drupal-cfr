<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\Configurator\Broken\BrokenConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

abstract class Configurator_IdConfBase extends Configurator_IdConfGrandBase {

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return array|null
   */
  protected function idConfGetOptionsForm($id, $optionsConf) {

    if (NULL === $configurator = $this->idGetConfigurator($id)) {
      return NULL;
    }

    if ($configurator instanceof BrokenConfiguratorInterface) {
      return NULL;
    }

    return $configurator->confGetForm($optionsConf, NULL);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return string|null
   */
  protected function idConfGetSummary($id, $optionsConf, SummaryBuilderInterface $summaryBuilder) {

    $idLabel = $this->idGetLabel($id);

    if (NULL === $id or NULL === $configurator = $this->idGetConfigurator($id)) {
      return $idLabel;
    }

    return $summaryBuilder->idConf($idLabel, $configurator, $optionsConf);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  public function idConfGetValue($id, $optionsConf) {

    if (!$configurator = $this->idGetConfigurator($id)) {
      return new BrokenValue($this, get_defined_vars(), 'Unknown id.');
    }

    return $configurator->confGetValue($optionsConf);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  abstract protected function idGetConfigurator($id);
}