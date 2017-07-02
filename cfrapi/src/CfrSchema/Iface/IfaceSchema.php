<?php

namespace Drupal\cfrapi\CfrSchema\Iface;

use Drupal\cfrapi\CfrSchema\Optional\OptionalSchema;
use Drupal\cfrapi\CfrSchema\Sequence\SequenceSchema;
use Drupal\cfrapi\Context\CfrContextInterface;

class IfaceSchema implements IfaceSchemaInterface {

  /**
   * @var string
   */
  private $interface;

  /**
   * @var \Drupal\cfrapi\Context\CfrContextInterface|NULL
   */
  private $context;

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrapi\CfrSchema\Sequence\SequenceSchema
   */
  public static function createSequence($interface, CfrContextInterface $context = NULL) {
    return new SequenceSchema(
      new self($interface, $context));
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   *
   * @return \Drupal\cfrapi\CfrSchema\Optional\OptionalSchemaInterface
   */
  public static function createOptional($interface, CfrContextInterface $context = NULL) {
    return new OptionalSchema(new self($interface, $context));
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
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
   * @return \Drupal\cfrapi\Context\CfrContextInterface|null
   */
  public function getContext() {
    return $this->context;
  }
}
