<?php
use Drupal\cfrplugin\Util\UiUtil;

/**
 * @param string $interface
 *
 * @return string
 */
function _cfrplugin_report_interface_code_page($interface) {

  $html = '';

  if (!interface_exists($interface) && !class_exists($interface)) {
    $html .= t('There is no class or interface by this name.');
  }
  elseif (null === $php = UiUtil::classGetPhp($interface)) {
    $html .= t('The class has no doc comment.');
  }
  else {
    $html .= UiUtil::highlightPhp($php);
  }

  return $html;
}
