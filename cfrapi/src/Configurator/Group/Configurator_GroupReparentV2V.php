<?php

namespace Drupal\cfrapi\Configurator\Group;

use Drupal\cfrapi\ValueToValue\ValueToValueInterface;

class Configurator_GroupReparentV2V extends Configurator_GroupReparent {

  /**
   * @var \Drupal\cfrapi\ValueToValue\ValueToValueInterface|null
   */
  private $valueToValue;

  /**
   * @param \Drupal\cfrapi\ValueToValue\ValueToValueInterface $valueToValue
   *
   * @return $this
   */
  public function setValueToValue(ValueToValueInterface $valueToValue) {
    $this->valueToValue = $valueToValue;
    return $this;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    if (NULL !== $this->valueToValue) {
      $value = $this->valueToValue->valueGetValue($value);
    }
    return $value;
  }
}
