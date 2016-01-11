<?php

namespace Drupal\cfrapi\ConfToValue;

interface ConfToValueInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  function confGetValue($conf);

}
