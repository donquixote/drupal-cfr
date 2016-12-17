<?php

namespace Drupal\cfrfamily\IdConfToPhp;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

/**
 * @see \Drupal\cfrfamily\IdConfToValue\IdConfToValueInterface::idConfGetValue()
 * @see \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface::idConfGetValue()
 */
interface IdConfToPhpInterface {

  /**
   * @param string|int $id
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  function idConfGetPhp($id, $conf, CfrCodegenHelperInterface $helper);

}
