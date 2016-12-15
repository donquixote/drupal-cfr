<?php

namespace Drupal\cfrapi\ConfToValue;

use Drupal\cfrapi\ConfToPhp\ConfToPhpInterface;

interface ConfToValueInterface extends ConfToPhpInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  public function confGetValue($conf);

}
