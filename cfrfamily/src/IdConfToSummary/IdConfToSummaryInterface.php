<?php

namespace Drupal\cfrfamily\IdConfToSummary;

interface IdConfToSummaryInterface {

  /**
   * @param string|null $id
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return string|null
   */
  function idConfGetSummary($id, $conf);

}
