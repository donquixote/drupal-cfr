<?php

use Drupal\cfrplugin\Util\UiCodeUtil;
use Drupal\cfrplugin\Util\UiDefinitionUtil;

/**
 * @param array $df
 *
 * @return string|array
 */
function _cfrplugin_report_definition_code_page(array $df) {

  $definition = $df['definition'];

  if (NULL !== $class = UiDefinitionUtil::definitionGetClass($definition)) {
    return UiCodeUtil::classGetCodeAsHtml($class);
  }
  else {
    return t('Cannot find a class name from the plugin definition.');
  }
}
