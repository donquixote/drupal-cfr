<?php

namespace Drupal\cfrrealm\TypeToConfigurator;


use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrrealm\TypeToContainer\TypeToContainerInterface;

/**
 * @deprecated
 */
class TypeToConfigurator_ViaContainer implements TypeToConfiguratorInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToContainer\TypeToContainerInterface
   */
  private $typeToContainer;

  /**
   * @param \Drupal\cfrrealm\TypeToContainer\TypeToContainerInterface $typeToContainer
   */
  function __construct(TypeToContainerInterface $typeToContainer) {
    $this->typeToContainer = $typeToContainer;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function typeGetConfigurator($type, CfrContextInterface $context = NULL) {
    return $this->typeToContainer->typeGetContainer($type, $context)->configurator;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function typeGetOptionalConfigurator($type, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    return $this->typeToContainer->typeGetContainer($type, $context)->optionalConfigurator;
  }
}
