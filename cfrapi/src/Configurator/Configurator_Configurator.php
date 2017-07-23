<?php

namespace Drupal\cfrapi\Configurator;

use Drupal\cfrapi\Util\UtilBase;

final class Configurator_Configurator extends UtilBase {

  /**
   * @Cf
   *
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function create(ConfiguratorInterface $schema) {
    return $schema;
  }
}
