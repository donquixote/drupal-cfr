<?php
namespace Drupal\cfrplugindiscovery\Hub;

interface CfrPluginDiscoveryHubInterface {

  /**
   * @param string $directory
   * @param string $namespace
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  public function discoverByInterface($directory, $namespace);
}
