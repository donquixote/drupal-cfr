<?php

namespace Drupal\cfrfamily\IdConfToPhp;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;

/**
 * @see \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface::idConfGetValue()
 * @see \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface::idConfGetValue()
 */
interface IdConfToPhpInterface {

  /**
   * @param string|int $id
   * @param mixed $conf
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  function idConfGetPhp($id, $conf, CodegenHelperInterface $helper);

}
