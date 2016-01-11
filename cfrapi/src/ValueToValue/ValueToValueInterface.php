<?php

namespace Drupal\cfrapi\ValueToValue;

interface ValueToValueInterface {

  /**
   * Processes or replaces the value.
   *
   * @param mixed $value
   *
   * @return mixed
   */
  function valueGetValue($value);

}
