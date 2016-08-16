<?php

namespace Drupal\cfrrealm\TypeToContainer;

use Drupal\cfrapi\Context\CfrContextInterface;

class TypeToContainer_Buffer implements TypeToContainerInterface {

  /**
   * Buffered containers by type.
   *
   * @var \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface[]
   *   Format: $[$type] = $container
   */
  private $containersByType = array();

  /**
   * Buffered containers by type and context.
   *
   * @var \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface[][]
   *   Format: $[$type][$contextKey] = $container
   */
  private $containersByTypeAndContext = array();

  /**
   * @var \Drupal\cfrrealm\TypeToContainer\TypeToContainerInterface
   */
  private $typeToContainer;

  /**
   * @param \Drupal\cfrrealm\TypeToContainer\TypeToContainerInterface $typeToContainer
   *   The decorated TypeToContainer.
   */
  function __construct(TypeToContainerInterface $typeToContainer) {
    $this->typeToContainer = $typeToContainer;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrfamily\CfrFamilyContainer\CfrFamilyContainerInterface
   */
  function typeGetContainer($type, CfrContextInterface $context = NULL) {
    if (NULL === $context) {
      return isset($this->containersByType[$type])
        ? $this->containersByType[$type]
        : $this->containersByType[$type] = $this->typeToContainer->typeGetContainer($type);
    }
    else {
      $contextKey = $context->getMachineName();
      return isset($this->containersByType[$type][$contextKey])
        ? $this->containersByTypeAndContext[$type][$contextKey]
        : $this->containersByTypeAndContext[$type][$contextKey] = $this->typeToContainer->typeGetContainer($type, $context);
    }
  }
}
