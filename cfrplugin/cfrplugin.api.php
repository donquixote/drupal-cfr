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
  $definitions = array();

  // Use the annotation-based discovery.
  $definitions += function_exists('cfrplugindiscovery')
    ? cfrplugindiscovery()->discoverByInterface(__DIR__ . '/src', 'Drupal\renderkit')
    : array();

  // Or do it manually.
  $definitions[EntityDisplayInterface::class] = array(
    'rawTitle' => array(
      'label' => t('Entity title, raw'),
      'configurator_factory' => array(EntityDisplay_Title::class, 'createConfigurator'),
    ),
    'title' => array(
      'label' => t('Entity title'),
      'handler_class' => EntityDisplay_Title::class,
    ),
    'fieldWithFormatter' => array(
      'label' => t('Field with formatter'),
      'configurator_factory' => array(EntityDisplay_FieldWithFormatter::class, 'createConfigurator')
    ),
  );

  return $definitions;
}
