<?php

namespace Drupal\cfrfamily\IdConfToValue;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

interface IdConfToValueInterface {

  /**
   * @param string|null $id
   * @param mixed $conf
   *
   * @return mixed
   */
  public function idConfGetValue($id, $conf);

  /**
   * @param string|int $id
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function idConfGetPhp($id, $conf, CfrCodegenHelperInterface $helper);

}
