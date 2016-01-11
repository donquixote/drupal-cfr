<?php

namespace Drupal\cfrapi\ConfToValue;

use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\ValueToValue\ValueToValueInterface;

trait V2VConfToValueTrait {

  /**
   * @var \Drupal\cfrapi\ValueToValue\ValueToValueInterface|null
   */
  private $valueToValue;

  /**
   * @param \Drupal\cfrapi\ValueToValue\ValueToValueInterface $valueToValue
   *
   * @return $this
   */
  function setValueToValue(ValueToValueInterface $valueToValue) {
    $this->valueToValue = $valueToValue;
    return $this;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  function confGetValue($conf) {
    $value = $this->confGetRawValue($conf);
    if (NULL !== $this->valueToValue) {
      if ($value instanceof BrokenValueInterface) {
        return $value;
      }
      $value = $this->valueToValue->valueGetValue($value);
    }
    return $value;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  abstract protected function confGetRawValue($conf);

}
