<?php

namespace Drupal\cfrplugin\Hub;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;
use Drupal\cfrplugin\DIC\CfrPluginRealmContainer;
use Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface;

class CfrPluginHub implements CfrPluginHubInterface {

  /**
   * @var \Drupal\cfrplugin\DIC\CfrPluginRealmContainer|null
   */
  private static $container;

  /**
   * @var \Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface
   */
  private $interfaceToConfigurator;

  /**
   * @return \Drupal\cfrplugin\DIC\CfrPluginRealmContainer
   */
  static function getContainer() {
    return NULL !== self::$container
      ? self::$container
      : self::$container = CfrPluginRealmContainer::createWithCache();
  }

  /**
   * @return \Drupal\cfrplugin\Hub\CfrPluginHubInterface
   */
  static function create() {
    return new self(self::getContainer()->interfaceToConfigurator);
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

  /**
   * @param string $interface
   *
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface|null
   */
  function interfaceGetCfrLegendOrNull($interface) {
    $configurator = $this->interfaceToConfigurator->interfaceGetConfigurator($interface);
    if (!$configurator instanceof CfrLegendProviderInterface) {
      return NULL;
    }
    return $configurator->getCfrLegend();
  }
}
