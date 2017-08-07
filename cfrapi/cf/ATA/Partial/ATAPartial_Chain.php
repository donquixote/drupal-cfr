<?php

namespace Donquixote\Cf\ATA\Partial;

use Donquixote\Cf\ATA\ATAInterface;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;
use Donquixote\Cf\Util\LocalPackageUtil;

class ATAPartial_Chain implements ATAPartialInterface {

  /**
   * @var \Donquixote\Cf\ATA\Partial\ATAPartialInterface[]
   */
  private $partials;

  /**
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue) {
    $partials = LocalPackageUtil::collectATAPartials($paramToValue);
    return new self($partials);
  }

  /**
   * @param \Donquixote\Cf\ATA\Partial\ATAPartialInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

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
    ATAInterface $helper
  ) {

    foreach ($this->partials as $mapper) {
      if (NULL !== $candidate = $mapper->cast($source, $interface, $helper)) {
        if ($candidate instanceof $interface) {
          return $candidate;
        }
      }
    }

    return NULL;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function providesResultType($interface) {
    return TRUE;
  }

  /**
   * @param string $interface
   *
   * @return bool
   */
  public function acceptsSourceClass($interface) {
    return TRUE;
  }

  /**
   * @return int
   */
  public function getSpecifity() {
    return 0;
  }
}
