<?php

namespace Drupal\cfrapi\ConfToPhp;

/**
 * @see \Drupal\cfrapi\ConfToValue\ConfToValueInterface::confGetValue()
 */
interface ConfToPhpInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  public function confGetPhp($conf);

}
