<?php

namespace Drupal\cfrrealm\TypeToCfrFamily;

use Drupal\cfrapi\Context\CfrContextInterface;

class TypeToCfrFamily_Buffer implements TypeToCfrFamilyInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamilyInterface
   */
  private $decorated;

  /**
   * @var \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface[]
   */
  private $buffer = [];

  /**
   * @param \Drupal\cfrrealm\TypeToCfrFamily\TypeToCfrFamilyInterface $decorated
   */
  function __construct(TypeToCfrFamilyInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamily\CfrFamilyInterface
   */
  function typeGetCfrFamily($type, CfrContextInterface $context = NULL) {
    $k = (NULL !== $context)
      ? $type . '::' . $context->getMachineName()
      : $type;
    return array_key_exists($k, $this->buffer)
      ? $this->buffer[$k]
      : $this->buffer[$k] = $this->decorated->typeGetCfrFamily($type, $context);
  }
}
