<?php

namespace Drupal\cfrfamily\IdPhpToPhp;

/**
 * @see \Drupal\cfrfamily\IdValueToValue\IdValueToValueInterface::idValueGetValue()
 */
interface IdPhpToPhpInterface {

  /**
   * @param string $id
   * @param string $php
   *   PHP code to generate a value.
   *
   * @return string
   *   PHP code to generate a value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  function idPhpGetPhp($id, $php);

}
