<?php

namespace Drupal\cfrfamily\CfrFamilyContainer;

/**
 * Contains objects that are specific to a configurator type.
 *
 * @property \Drupal\cfrapi\Legend\LegendInterface $legend
 * @property \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
 * @property \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface $optionalConfigurator
 * @property \Drupal\cfrfamily\CfrLegend\CfrLegendInterface $cfrLegend
 * @property \Drupal\cfrfamily\IdToConfigurator\IdToConfiguratorInterface $idToConfigurator
 * @property \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface $confEmptyness
 */
interface CfrFamilyContainerInterface {

}
