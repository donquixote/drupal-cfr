<?php

namespace Drupal\cfrplugin\Hub;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrplugin\DIC\CfrPluginRealmContainer;
use Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface;

class CfrPluginHub implements CfrPluginHubInterface {

  /**
   * @var \Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface
   */
  private $interfaceToConfigurator;

  /**
   * @return \Drupal\cfrplugin\Hub\CfrPluginHubInterface
   */
  static function create() {
    $container = CfrPluginRealmContainer::createWithoutCache();
    return new self($container->interfaceToConfigurator);
  }

  /**
   * @param \Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface $interfaceToConfigurator
   */
  function __construct(InterfaceToConfiguratorInterface $interfaceToConfigurator) {
    $this->interfaceToConfigurator = $interfaceToConfigurator;
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function interfaceGetConfigurator($interface, CfrContextInterface $context = NULL) {
    return $this->interfaceToConfigurator->interfaceGetConfigurator($interface, $context);
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  function interfaceGetOptionalConfigurator($interface, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    return $this->interfaceToConfigurator->interfaceGetOptionalConfigurator($interface, $context, $defaultValue);
  }
}
