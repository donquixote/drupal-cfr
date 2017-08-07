<?php

namespace Donquixote\Cf\ATA;

interface ATAInterface {

  /**
   * @param mixed $source
   * @param string $interface
   *   Interface for destination value.
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function cast($source, $interface);


}
