<?php

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Util\StaUtil;
use Drupal\cfrplugin\Hub\CfrPluginHub;

/**
 * @param array $element
 * @param mixed|false $input
 * @param array $form_state
 *
 * @return mixed
 */
function _cfrapi_cf_schema_element_value(array $element, $input,
  /** @noinspection PhpUnusedParameterInspection */ array &$form_state) {

  dpm(get_defined_vars(), __FUNCTION__ . '() - get_defined_vars()');

  if (FALSE === $input) {

    dpm('Use $element[#default_value].');

    return isset($element['#default_value'])
      ? $element['#default_value']
      : [];
  }

  return $input;
}

/**
 * @param array $element
 * @param array $form_state
 *
 * @return array
 */
function _cfrapi_cf_schema_element_process(array $element,
  /** @noinspection PhpUnusedParameterInspection */ array &$form_state) {

  if (!isset($element['#cf_schema'])) {
    return $element;
  }

  $schema = $element['#cf_schema'];

  if (!$schema instanceof CfSchemaInterface) {
    return $element;
  }

  $container = CfrPluginHub::getContainer();

  $formator = StaUtil::formator(
    $schema,
    $container->schemaToAnything);

  if (NULL === $formator) {
    return $element;
  }

  $element['schema'] = $formator->confGetD7Form(
    $element['#value'], $element['#title']
  );

  $element['schema']['#parents'] = $element['#parents'];

  return $element;
}
