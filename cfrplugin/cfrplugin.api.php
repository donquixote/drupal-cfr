<?php
use Drupal\renderkit\EntityDisplay\EntityDisplay_FieldWithFormatter;
use Drupal\renderkit\EntityDisplay\EntityDisplay_Title;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;

/**
 * Implements hook_cfrplugin_info()
 *
 * @return array[][]
 */
function hook_cfrplugin_info() {
  $definitions = [];

  // Use the annotation-based discovery.
  $definitions += function_exists('cfrplugindiscovery')
    ? cfrplugindiscovery()->discoverByInterface(__DIR__ . '/src', 'Drupal\renderkit')
    : [];

  // Or do it manually.
  $definitions[EntityDisplayInterface::class] = [
    'rawTitle' => [
      'label' => t('Entity title, raw'),
      'configurator_factory' => [EntityDisplay_Title::class, 'createConfigurator'],
    ],
    'title' => [
      'label' => t('Entity title'),
      'handler_class' => EntityDisplay_Title::class,
    ],
    'fieldWithFormatter' => [
      'label' => t('Field with formatter'),
      'configurator_factory' => [EntityDisplay_FieldWithFormatter::class, 'createConfigurator']
    ],
  ];

  return $definitions;
}
