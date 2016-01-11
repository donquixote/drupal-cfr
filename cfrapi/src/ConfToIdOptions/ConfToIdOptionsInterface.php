<?php

namespace Drupal\cfrapi\ConfToIdOptions;

interface ConfToIdOptionsInterface {

  /**
   * @param mixed $conf
   *
   * @return array
   *   Format: array($id, $optionsConf)
   */
  function confGetIdOptions($conf);

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  function idOptionsToConf($id, $optionsConf);

}
