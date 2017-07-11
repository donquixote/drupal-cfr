<?php

namespace Donquixote\Cf\Schema\Iface;

use Donquixote\Cf\Schema\Optional\CfSchema_Optional;
use Donquixote\Cf\Schema\Sequence\CfSchema_Sequence;
use Donquixote\Cf\Context\CfContextInterface;

class CfSchema_Iface implements CfSchema_IfaceInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|NULL
   */
  private $context;

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\Sequence\CfSchema_Sequence
   */
  public static function createSequence($interface, CfContextInterface $context = NULL) {
    return new CfSchema_Sequence(
      new self($interface, $context));
  }

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfContextInterface|NULL $context
   *
   * @return \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  public static function createOptional($interface, CfContextInterface $context = NULL) {
    return new CfSchema_Optional(new self($interface, $context));
  }

  /**
   * @param string $interface
   * @param \Donquixote\Cf\Context\CfContextInterface|NULL $context
   */
  public function __construct($interface, CfContextInterface $context = NULL) {
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
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext() {
    return $this->context;
  }
}
