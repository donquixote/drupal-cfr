<?php

namespace Drupal\cfrplugin\ParamConverter;

use Drupal\cfrplugin\Hub\CfrPluginHub;

class ParamConverter_PluginDefinition extends ParamConverterBase {

  const TYPE = 'cfrplugin:definition';

  /**
   * Converts path variables to their corresponding objects.
   *
   * @param mixed $value
   *   The raw value.
   * @param mixed $param_definition
   *   The parameter definition provided in the route options.
   * @param string $name
   *   The name of the parameter.
   * @param array $defaults
   *   The route defaults array.
   *
   * @return mixed|null
   *   The converted parameter value.
   */
  public function convert($value, $param_definition, $name, array $defaults) {

    if (empty($defaults['interface'])) {
      return FALSE;
    }

    $interface = $defaults['interface'];

    if (NULL === $plugin_definition = CfrPluginHub::getContainer()
        ->typeToDefmap
        ->typeGetDefmap($interface)
        ->idGetDefinition($value)
    ) {
      return FALSE;
    }

    return [
      'interface' => $interface,
      'id' => $value,
      'definition' => $plugin_definition,
    ];
  }
}
