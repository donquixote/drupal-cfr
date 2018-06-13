<?php

use Drupal\cfrplugin\Util\DefinitionUtil;
use Drupal\cfrplugin\Util\UiUtil;

/**
 * @param array $df
 *
 * @return string|array
 */
function _cfrplugin_report_definition_code_page(array $df) {

  $definition = $df['definition'];

  $file = DefinitionUtil::definitionGetFile($definition);

  $html = '';

  if (NULL === $file) {
    $html .= t('No PHP file could be found for the definition.');
  }
  elseif (!file_exists($file)) {
    $html .= t('File does not exist: @file', ['@file' => $file]);
  }
  elseif (!is_readable($file)) {
    $html .= t('File is not readable: @file', ['@file' => $file]);
  }
  else {
    $php = file_get_contents($file);
    $html .= UiUtil::highlightPhp($php);
  }

  return $html;
}
