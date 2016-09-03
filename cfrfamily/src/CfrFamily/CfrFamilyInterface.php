<?php
namespace Drupal\cfrfamily\CfrFamily;

interface CfrFamilyInterface {

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  public function getCfrLegend();

  /**
   * @return \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface
   */
  public function getFamilyConfigurator();

  /**
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function getOptionalFamilyConfigurator($defaultValue = NULL);
}
