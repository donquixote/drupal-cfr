<?php

namespace Drupal\cfrfamily\IdValueToValue;

interface IdValueToValueInterface {

  /**
   * @param string $id
   * @param mixed $value
   *   Value from $this->idGetConfigurator($id)->confGetValue($conf)
   *
   * @return mixed
   *   Transformed or combined value.
   */
  function idValueGetValue($id, $value);

}
