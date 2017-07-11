<?php

namespace Donquixote\Cf\Helper;

interface SchemaHelperBaseInterface {

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return mixed
   */
  public function incompatibleConfiguration($conf, $message);

  /**
   * @param string $message
   *
   * @return mixed
   */
  public function invalidConfiguration($message);

}
