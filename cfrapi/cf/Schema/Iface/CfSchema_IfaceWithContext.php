<?php

namespace Donquixote\Cf\Schema\Iface;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional;
use Donquixote\Cf\Schema\Sequence\CfSchema_Sequence;

class CfSchema_IfaceWithContext implements CfSchema_IfaceWithContextInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|NULL
   */
  private $context;

  /**
   * @var string
   */
  private $cacheId;

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
   * @param $interface
   * @param \Donquixote\Cf\Context\CfContextInterface|NULL $context
   * @param bool $required
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function create($interface, CfContextInterface $context = NULL, $required = TRUE) {
    $schema = new self($interface, $context);
    if (!$required) {
      $schema = new CfSchema_Optional($schema);
    }
    return $schema;
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

  /**
   * @return string
   */
  public function getCacheId() {
    return NULL !== $this->cacheId
      ? $this->cacheId
      : $this->cacheId = $this->buildCacheId();
  }

  private function buildCacheId() {

    $id = $this->interface;

    if (NULL !== $this->context) {
      $id .= $this->context->getMachineName();
    }

    return $id;
  }
}
