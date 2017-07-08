<?php

use Drupal\cfrplugin\Util\UiCodeUtil;

/**
 * @param string $interface
 *
 * @return string
 */
function _cfrplugin_report_interface_code_page($interface) {

  return UiCodeUtil::classGetCodeAsHtml($interface);
}
