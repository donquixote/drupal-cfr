<?php

namespace Drupal\cfrplugin\InterfaceToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface;
use Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface;

class InterfaceToConfigurator_ViaTypeIdentity implements InterfaceToConfiguratorInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  private $typeToConfigurator;

  /**
   * @param \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $typeToConfigurator
   */
  public function __construct(TypeToConfiguratorInterface $typeToConfigurator) {
    $this->typeToConfigurator = $typeToConfigurator;
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function interfaceGetConfigurator($interface, CfrContextInterface $context = NULL) {
    return $this->typeToConfigurator->typeGetConfigurator($interface, $context);
  }

  /**
   * @param string $interface
   *   Qualified class name of an interface.
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function interfaceGetOptionalConfigurator($interface, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    return $this->typeToConfigurator->typeGetOptionalConfigurator($interface, $context, $defaultValue);
  }
}
