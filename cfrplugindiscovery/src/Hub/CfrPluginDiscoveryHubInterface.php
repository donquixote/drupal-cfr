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

  /**
   * Version of discoverByInterface() that does not require specifying the
   * module name.
   *
   * @param $__FILE__
   *   Path to a *.module file.
   *   Since this is usually called from such a file, modules will usually pass
   *   __FILE__.
   *
   * @return array[][]
   *   Format: $[$pluginType][$pluginId] = $pluginDefinition
   */
  public function moduleFileScanPsr4($__FILE__);
}
