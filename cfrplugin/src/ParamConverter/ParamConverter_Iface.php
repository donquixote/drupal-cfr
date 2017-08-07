<?php

namespace Drupal\cfrplugin\ParamConverter;

use Drupal\cfrplugin\Util\UiUtil;

class ParamConverter_Iface extends ParamConverterBase {

  const TYPE = 'cfrplugin:interface';

  /**
   * Converts path variables to their corresponding objects.
   *
   * @param mixed $value
   *   The raw value.
   * @param mixed $definition
   *   The parameter definition provided in the route options.
   * @param string $name
   *   The name of the parameter.
   * @param array $defaults
   *   The route defaults array.
   *
   * @return mixed|null
   *   The converted parameter value.
   */
  public function convert($value, $definition, $name, array $defaults) {

    $interface = str_replace('.', '\\', $value);

    if (!UiUtil::interfaceNameIsValid($interface)) {
      return FALSE;
    }

    // At this point, $interface looks like a valid class name. But it could still
    // be a non-existing interface, and possibly something ridiculously long.
    // Avoid interface_exists(), because autoload can have side effects.
    return $interface;
  }
}
