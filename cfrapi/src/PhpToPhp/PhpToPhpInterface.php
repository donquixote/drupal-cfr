<?php

namespace Drupal\cfrapi\PhpToPhp;

/**
 * @see \Drupal\cfrapi\ValueToValue\ValueToValueInterface
 */
interface PhpToPhpInterface {

  /**
   * @param string $php
   *   PHP code to generate a value.
   *
   * @return string
   *   Modified PHP code to generate a value.
   */
  public function phpGetPhp($php);

}
