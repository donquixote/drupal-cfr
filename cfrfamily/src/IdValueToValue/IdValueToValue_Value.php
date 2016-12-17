<?php

namespace Drupal\cfrfamily\IdValueToValue;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

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
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP code to generate a value.
   */
  public function idPhpGetPhp($id, $php, CfrCodegenHelperInterface $helper) {
    return $php;
  }
}
