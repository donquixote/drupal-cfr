<?php

namespace Drupal\cfrfamily\IdConfToPhp;

/**
 * @see \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface::idConfGetValue()
 * @see \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface::idConfGetValue()
 */
interface IdConfToPhpInterface {

  /**
   * @param string|int $id
   * @param mixed $conf
   *
   * @return string
   *   PHP statement to generate the value.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   * @throws \Drupal\cfrapi\Exception\BrokenConfiguratorException
   */
  function idConfGetPhp($id, $conf);

}
