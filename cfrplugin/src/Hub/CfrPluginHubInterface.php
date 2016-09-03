<?php
namespace Drupal\cfrplugin\Hub;

use Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface;

interface CfrPluginHubInterface extends InterfaceToConfiguratorInterface {

  /**
   * @param string $interface
   *
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface
   */
  public function interfaceGetCfrLegendOrNull($interface);
}
