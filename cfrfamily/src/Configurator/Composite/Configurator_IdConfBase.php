<?php

namespace Drupal\cfrfamily\Configurator\Composite;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrfamily\IdConfToPhp\IdConfToPhpInterface;

abstract class Configurator_IdConfBase extends Configurator_IdConfGrandBase implements IdConfToPhpInterface {

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

    return $configurator->confGetForm($optionsConf, $this->idGetOptionsFormLabel($id));
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  protected function idGetOptionsFormLabel($id) {
    return NULL;
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
   * @param mixed $conf
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function idConfGetPhp($id, $conf, CodegenHelperInterface $helper) {

    if (!$configurator = $this->idGetConfigurator($id)) {
      return $helper->incompatibleConfiguration($id, "Unknown id.");
    }

    return $configurator->confGetPhp($conf, $helper);
  }

  /**
   * @param string $id
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|null
   */
  abstract protected function idGetConfigurator($id);
}
