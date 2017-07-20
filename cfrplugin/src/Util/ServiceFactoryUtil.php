<?php

namespace Drupal\cfrplugin\Util;

use Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface;
use Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfigurator_ConfiguratorObject;
use Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfigurator_FixedValue;
use Drupal\cfrapi\Util\UtilBase;
use Drupal\cfrreflection\CfrGen\ArgDefToConfigurator\ArgDefToConfigurator_Callback;
use Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfigurator_ConfiguratorFactory;
use Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfigurator_SchemaFactory;
use Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface;
use Drupal\cfrreflection\ValueToCallback\CallableToCallback;
use Drupal\cfrreflection\ValueToCallback\ClassNameToCallback;

final class ServiceFactoryUtil extends UtilBase {

  /**
   * @param \Drupal\cfrreflection\CfrGen\CallbackToConfigurator\CallbackToConfiguratorInterface $valueCtc
   * @param \Drupal\cfrapi\SchemaToConfigurator\SchemaToConfiguratorInterface $schemaToConfigurator
   *
   * @return \Drupal\cfrfamily\ArgDefToConfigurator\ArgDefToConfiguratorInterface[]
   */
  public static function createDeftocfrMappers(
    CallbackToConfiguratorInterface $valueCtc,
    SchemaToConfiguratorInterface $schemaToConfigurator
  ) {
    $classToCallback = new ClassNameToCallback();
    $callbackToCallback = new CallableToCallback();
    $cfrFactoryCtc = new CallbackToConfigurator_ConfiguratorFactory();
    $schemaFactoryCtc = new CallbackToConfigurator_SchemaFactory($schemaToConfigurator);

    return [
      'configurator' => new ArgDefToConfigurator_ConfiguratorObject(),
      'configurator_class' => new ArgDefToConfigurator_Callback($classToCallback, 'configurator_arguments', $cfrFactoryCtc),
      'configurator_factory' => new ArgDefToConfigurator_Callback($callbackToCallback, 'configurator_arguments', $cfrFactoryCtc),
      'schema' => new ArgDefToConfigurator_ConfiguratorObject(),
      'schema_class' => new ArgDefToConfigurator_Callback($classToCallback, 'schema_arguments', $schemaFactoryCtc),
      'schema_factory' => new ArgDefToConfigurator_Callback($callbackToCallback, 'schema_arguments', $schemaFactoryCtc),
      'handler' => new ArgDefToConfigurator_FixedValue(),
      'handler_class' => new ArgDefToConfigurator_Callback($classToCallback, 'handler_arguments', $valueCtc),
      'handler_factory' => new ArgDefToConfigurator_Callback($callbackToCallback, 'handler_arguments', $valueCtc),
      'class' => new ArgDefToConfigurator_Callback($classToCallback, 'configurator_arguments', $cfrFactoryCtc),
      'factory' => new ArgDefToConfigurator_Callback($callbackToCallback, 'configurator_arguments', $cfrFactoryCtc),
    ];
  }
}
