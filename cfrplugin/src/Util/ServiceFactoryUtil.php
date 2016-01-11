<?php

namespace Drupal\cfrplugin\Util;

use Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfigurator_ConfiguratorObject;
use Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfigurator_FixedValue;
use Drupal\cfrapi\Util\UtilBase;
use Drupal\cfrreflection\CfrGen\ArgDefToConfigurator\ArgDefToConfigurator_Callback;
use Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfigurator_ConfiguratorFactory;
use Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface;
use Drupal\cfrreflection\ValueToCallback\CallableToCallback;
use Drupal\cfrreflection\ValueToCallback\ClassNameToCallback;

final class ServiceFactoryUtil extends UtilBase {

  /**
   * @param \Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface $valueCtc
   *
   * @return \Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfiguratorInterface[]
   */
  static function createDeftocfrMappers(CallbackToConfiguratorInterface $valueCtc) {
    $classToCallback = new ClassNameToCallback();
    $callbackToCallback = new CallableToCallback();
    $cfrFactoryCtc = new CallbackToConfigurator_ConfiguratorFactory();
    return array(
      'configurator' => new ArgDefToConfigurator_ConfiguratorObject(),
      'configurator_class' => new ArgDefToConfigurator_Callback($classToCallback, 'configurator_arguments', $cfrFactoryCtc),
      'configurator_factory' => new ArgDefToConfigurator_Callback($callbackToCallback, 'configurator_arguments', $cfrFactoryCtc),
      'handler' => new ArgDefToConfigurator_FixedValue(),
      'handler_class' => new ArgDefToConfigurator_Callback($classToCallback, 'handler_arguments', $valueCtc),
      'handler_factory' => new ArgDefToConfigurator_Callback($callbackToCallback, 'handler_arguments', $valueCtc),
      'class' => new ArgDefToConfigurator_Callback($classToCallback, 'configurator_arguments', $cfrFactoryCtc),
      'factory' => new ArgDefToConfigurator_Callback($callbackToCallback, 'configurator_arguments', $cfrFactoryCtc),
    );
  }
}
