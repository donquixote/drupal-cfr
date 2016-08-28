<?php

namespace Drupal\cfrfamily\CfrFamilyContainer;

use Donquixote\Containerkit\Container\ContainerBase;

abstract class CfrFamilyContainerBase extends ContainerBase implements CfrFamilyContainerInterface {

  /**
   * @return \Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMap_FromDefinitionMap
   *
   * @see $configuratorMap
   */
  abstract protected function get_configuratorMap();

  /**
   * @return \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   *
   * @see $idToConfigurator
   */
  abstract protected function get_idToConfigurator();

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @see $configurator
   */
  abstract protected function get_configurator();

  /**
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   *
   * @see $optionalConfigurator
   */
  abstract protected function get_optionalConfigurator();

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   *
   * @see $cfrLegend
   */
  abstract protected function get_cfrLegend();

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   *
   * @see $confEmptyness
   */
  abstract protected function get_confEmptyness();

}
