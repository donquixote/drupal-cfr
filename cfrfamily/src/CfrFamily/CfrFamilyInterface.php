<?php
namespace Drupal\cfrfamily\CfrFamily;

interface CfrFamilyInterface {

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  function getCfrLegend();

  /**
   * @return \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface
   */
  function getFamilyConfigurator();

  /**
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function getOptionalFamilyConfigurator($defaultValue = NULL);
}
