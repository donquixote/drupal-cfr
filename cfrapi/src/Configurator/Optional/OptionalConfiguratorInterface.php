<?php

namespace Drupal\cfrapi\Configurator\Optional;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;

/**
 * An "optional" configurator is one where some configurations may count as
 * "empty". This is relevant especially for sequence configurators, where
 * "non-empty" items cause the sequence to grow.
 *
 * @see \Drupal\cfrapi\Configurator\Sequence\Configurator_Sequence
 */
interface OptionalConfiguratorInterface extends ConfiguratorInterface {

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   *   An emptyness object, or
   *   NULL, if the configurator is in fact required and thus no valid conf
   *   counts as empty.
   */
  public function getEmptyness();

}
