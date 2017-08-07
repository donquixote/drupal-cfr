<?php

namespace Donquixote\Cf\ATA\Partial;

use Donquixote\Cf\ATA\ATAInterface;

interface ATAPartialInterface {

  /**
   * @param mixed $source
   * @param string $interface
   * @param \Donquixote\Cf\ATA\ATAInterface $helper
   *
   * @return null|object An instance of $interface, or NULL.
   * An instance of $interface, or NULL.
   */
  public function cast(
    $source,
    $interface,
    ATAInterface $helper);

  /**
   * @param string $resultInterface
   *
   * @return bool
   */
  public function providesResultType($resultInterface);

  /**
   * @param string $sourceClass
   *
   * @return bool
   */
  public function acceptsSourceClass($sourceClass);

  /**
   * @return int
   */
  public function getSpecifity();

}
