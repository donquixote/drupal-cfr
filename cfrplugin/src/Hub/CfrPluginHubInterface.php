<?php
namespace Drupal\cfrplugin\Hub;

use Drupal\cfrreflection\CfrGen\InterfaceToConfigurator\InterfaceToConfiguratorInterface;

interface CfrPluginHubInterface extends InterfaceToConfiguratorInterface {

  /**
   * @return string[]
   *   Format: $[$interface] = $label
   */
  public function getInterfaceLabels();
}
