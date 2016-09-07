<?php

namespace Drupal\cfrplugin\Hub;

use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;
use Drupal\cfrplugin\DIC\CfrPluginRealmContainer;
use Drupal\cfrplugin\DIC\CfrPluginRealmContainerInterface;
use Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface;
use Drupal\cfrreflection\Util\StringUtil;

class CfrPluginHub implements CfrPluginHubInterface {

  /**
   * @var \Drupal\cfrplugin\DIC\CfrPluginRealmContainer|null
   */
  private static $container;

  /**
   * @var \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  private $interfaceToConfigurator;

  /**
   * @var \Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface
   */
  private $definitionsByTypeAndId;

  /**
   * @return \Drupal\cfrplugin\DIC\CfrPluginRealmContainer
   */
  public static function getContainer() {
    return NULL !== self::$container
      ? self::$container
      : self::$container = CfrPluginRealmContainer::createWithCache();
  }

  /**
   * @return \Drupal\cfrplugin\Hub\CfrPluginHubInterface
   */
  public static function create() {
    return self::createFromContainer(self::getContainer());
  }

  /**
   * @param \Drupal\cfrplugin\DIC\CfrPluginRealmContainerInterface $container
   *
   * @return \Drupal\cfrplugin\Hub\CfrPluginHub
   */
  public static function createFromContainer(CfrPluginRealmContainerInterface $container) {
    return new self(
      $container->typeToConfigurator,
      $container->definitionsByTypeAndId);
  }

  /**
   * @param \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $interfaceToConfigurator
   * @param \Drupal\cfrrealm\DefinitionsByTypeAndId\DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
   */
  public function __construct(
    TypeToConfiguratorInterface $interfaceToConfigurator,
    DefinitionsByTypeAndIdInterface $definitionsByTypeAndId
  ) {
    $this->interfaceToConfigurator = $interfaceToConfigurator;
    $this->definitionsByTypeAndId = $definitionsByTypeAndId;
  }

  /**
   * @return string[]
   */
  public function getInterfaceLabels() {

    $labels = [];
    foreach ($this->definitionsByTypeAndId->getDefinitionsByTypeAndId() as $interface => $definitions) {
      $labels[$interface] = StringUtil::interfaceGenerateLabel($interface);
    }

    return $labels;
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function interfaceGetConfigurator($interface, CfrContextInterface $context = NULL) {
    return $this->interfaceToConfigurator->typeGetConfigurator($interface, $context);
  }

  /**
   * @param string $interface
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function interfaceGetOptionalConfigurator($interface, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    return $this->interfaceToConfigurator->typeGetOptionalConfigurator($interface, $context, $defaultValue);
  }

  /**
   * @param string $interface
   *
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface|null
   */
  public function interfaceGetCfrLegendOrNull($interface) {
    $configurator = $this->interfaceToConfigurator->typeGetConfigurator($interface);
    if (!$configurator instanceof CfrLegendProviderInterface) {
      return NULL;
    }
    return $configurator->getCfrLegend();
  }
}
