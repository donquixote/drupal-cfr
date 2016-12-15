<?php

namespace Drupal\cfrfamily\IdValueToValue;

use Drupal\cfrfamily\IdPhpToPhp\IdPhpToPhpInterface;

interface IdValueToValueInterface extends IdPhpToPhpInterface {

  /**
   * @param string $id
   * @param mixed $value
   *   Value from $this->idGetConfigurator($id)->confGetValue($conf)
   *
   * @return mixed
   *   Transformed or combined value.
   */
  public function idValueGetValue($id, $value);

}
