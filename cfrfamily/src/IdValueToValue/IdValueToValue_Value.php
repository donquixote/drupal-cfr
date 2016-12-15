<?php

namespace Drupal\cfrfamily\IdValueToValue;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;

class IdValueToValue_Value implements IdValueToValueInterface {

  /**
   * @param string $id
   * @param mixed $value
   *   Value from $this->idGetConfigurator($id)->confGetValue($conf)
   *
   * @return mixed
   *   Transformed or combined value.
   */
  public function idValueGetValue($id, $value) {
    return $value;
  }

  /**
   * @param string $id
   * @param string $php
   *   PHP code to generate a value.
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP code to generate a value.
   */
  public function idPhpGetPhp($id, $php, CodegenHelperInterface $helper) {
    return $php;
  }
}
