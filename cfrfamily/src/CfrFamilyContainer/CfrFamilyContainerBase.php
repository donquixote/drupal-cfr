<?php

namespace Drupal\cfrfamily\CfrFamilyContainer;

use Donquixote\Containerkit\Container\ContainerBase;

abstract class CfrFamilyContainerBase extends ContainerBase implements CfrFamilyContainerInterface {

  /**
   * @return \Drupal\cfrfamily\ConfiguratorMap\ConfiguratorMap_FromDefinitionMap
   *
   * @see \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface::$configuratorMap
   */
  abstract protected function get_configuratorMap();

  /**
   * @return \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface
   *
   * @see \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface::$idToConfigurator
   */
  abstract protected function get_idToConfigurator();

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @see \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface::$configurator
   */
  abstract protected function get_configurator();

  /**
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   *
   * @see \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface::$optionalConfigurator
   */
  abstract protected function get_optionalConfigurator();

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   *
   * @see \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface::$cfrLegend
   */
  abstract protected function get_cfrLegend();

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   *
   * @see \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface::$confEmptyness
   */
  abstract protected function get_confEmptyness();

}
