<?php

namespace Drupal\cfrplugin\TypeToConfigurator;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\Configurator\Composite\Configurator_IdConfGrandBase;
use Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface;

class TypeToConfigurator_CfrPlugin implements TypeToConfiguratorInterface {

  /**
   * @var \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface
   */
  private $decorated;

  /**
   * @param \Drupal\cfrrealm\TypeToConfigurator\TypeToConfiguratorInterface $decorated
   */
  public function __construct(TypeToConfiguratorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public function typeGetConfigurator($type, CfrContextInterface $context = NULL) {
    $configurator = $this->decorated->typeGetConfigurator($type, $context);
    return $this->processConfigurator($configurator, $type, $context);
  }

  /**
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface|NULL $context
   * @param mixed $defaultValue
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   */
  public function typeGetOptionalConfigurator($type, CfrContextInterface $context = NULL, $defaultValue = NULL) {
    $configurator = $this->decorated->typeGetOptionalConfigurator($type, $context, $defaultValue);
    return $this->processConfigurator($configurator, $type, $context);
  }

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $configurator
   * @param string $type
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private function processConfigurator(ConfiguratorInterface $configurator, $type, CfrContextInterface $context = NULL) {

    if ($configurator instanceof Configurator_IdConfGrandBase) {
      $configurator = $configurator->withFormProcessCallback(function($element) use ($type, $context) {
        /* @see cfrplugin_element_info() */
        $element['#type'] = 'cfrplugin_drilldown_container';
        $element['#cfrplugin_interface'] = $type;
        $element['#cfrplugin_context'] = $context;
        return $element;
      });
    }

    return $configurator;
  }
}
