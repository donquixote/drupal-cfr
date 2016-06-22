<?php

namespace Drupal\cfrapi\PhpToPhp;

interface PhpToPhpInterface {

  /**
   * @param string $php
   *   PHP code to generate a value.
   *
   * @return string
   *   Modified PHP code to generate a value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  public function phpGetPhp($php);

}
