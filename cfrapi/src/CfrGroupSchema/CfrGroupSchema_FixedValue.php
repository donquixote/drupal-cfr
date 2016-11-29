<?php

namespace Drupal\cfrapi\CfrGroupSchema;

class CfrGroupSchema_FixedValue implements CfrGroupSchemaInterface {

  /**
   * @var mixed
   */
  private $value;

  /**
   * @param mixed $value
   */
  public function __construct($value) {
    $this->value = $value;
  }

  /**
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface[]
   */
  public function getConfigurators() {
    return [];
  }

  /**
   * @return string[]
   */
  public function getLabels() {
    return [];
  }

  /**
   * @param mixed[] $values
   *   Values returned from group configurators.
   *
   * @return mixed
   */
  public function valuesGetValue(array $values) {
    return $this->value;
  }
}
