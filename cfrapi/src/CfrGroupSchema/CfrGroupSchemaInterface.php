<?php

namespace Drupal\cfrapi\CfrGroupSchema;

interface CfrGroupSchemaInterface {

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  public function getConfigurators();

  /**
   * @return string[]
   */
  public function getLabels();

  /**
   * @param mixed[] $values
   *   Values returned from group configurators.
   *
   * @return mixed
   */
  public function valuesGetValue(array $values);

}
