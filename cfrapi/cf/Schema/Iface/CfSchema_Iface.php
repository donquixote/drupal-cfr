<?php

namespace Donquixote\Cf\Schema\Iface;

use Donquixote\Cf\Schema\Optional\CfSchema_Optional;
use Donquixote\Cf\Schema\Sequence\CfSchema_Sequence;
use Donquixote\Cf\Context\CfrContextInterface;

class CfSchema_Iface implements CfSchema_IfaceInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\Cf\Context\CfrContextInterface|NULL
   */
  private $context;

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfrContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\Sequence\CfSchema_Sequence
   */
  public static function createSequence($interface, CfrContextInterface $context = NULL) {
    return new CfSchema_Sequence(
      new self($interface, $context));
  }

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfrContextInterface|NULL $context
   *
   * @return \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  public static function createOptional($interface, CfrContextInterface $context = NULL) {
    return new CfSchema_Optional(new self($interface, $context));
  }

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfrContextInterface|NULL $context
   */
  public function __construct($interface, CfrContextInterface $context = NULL) {
    $this->interface = $interface;
    $this->context = $context;
  }

  /**
   * @return string
   */
  public function getInterface() {
    return $this->interface;
  }

  /**
   * @return \Donquixote\Cf\Context\CfrContextInterface|null
   */
  public function getContext() {
    return $this->context;
  }
}
