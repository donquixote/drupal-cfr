<?php

namespace Drupal\cfrfamily\IdValueToValue;

class IdValueToValue_Value implements IdValueToValueInterface {

  /**
   * @param string $id
   * @param mixed $value
   *   Value from $this->idGetConfigurator($id)->confGetValue($conf)
   *
   * @return mixed
   *   Transformed or combined value.
   */
  function idValueGetValue($id, $value) {
    return $value;
  }
}
