<?php

namespace Drupal\cfrapi\CfrSchema;

interface CfrSchemaInterface {

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function getConfigurator();

  /**
   * @param mixed $value
   *   Value from the provided configurator.
   *
   * @return mixed
   *   Processed value.
   */
  public function valueGetValue($value);

}
