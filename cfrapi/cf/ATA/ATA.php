<?php

namespace Donquixote\Cf\ATA;

use Donquixote\Cf\ATA\Partial\ATAPartial_SmartChain;
use Donquixote\Cf\ATA\Partial\ATAPartialInterface;
use Donquixote\Cf\ParamToValue\ParamToValueInterface;

class ATA implements ATAInterface {

  /**
   * @var \Donquixote\Cf\ATA\Partial\ATAPartialInterface
   */
  private $partial;

  /**
   * @param \Donquixote\Cf\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return self
   */
  public static function create(ParamToValueInterface $paramToValue) {
    return new self(ATAPartial_SmartChain::create($paramToValue));
  }

  /**
   * @param \Donquixote\Cf\ATA\Partial\ATAPartialInterface[] $partials
   *
   * @return self
   */
  public static function createFromPartials(array $partials) {
    return new self(new ATAPartial_SmartChain($partials));
  }

  /**
   * @param \Donquixote\Cf\ATA\Partial\ATAPartialInterface $partial
   */
  public function __construct(ATAPartialInterface $partial) {
    $this->partial = $partial;
  }

  /**
   * @param mixed $source
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or NULL.
   */
  public function cast($source, $interface) {

    if ($source instanceof $interface) {
      return $source;
    }

    return $this->partial->cast($source, $interface, $this);
  }
}
