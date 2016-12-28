<?php

namespace Drupal\cfrreflection\Util;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Exception\InvalidConfigurationException;
use Drupal\cfrapi\Util\UtilBase;

final class CfrReflectionUtil extends UtilBase {

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param array|mixed $args
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public static function callbackValidateAndInvoke(CallbackReflectionInterface $callback, $args) {

    if (!is_array($args)) {
      throw new InvalidConfigurationException("Non-array callback arguments");
    }

    CfrReflectionUtil::callbackAssertValidArgs($callback, $args);

    try {
      return $callback->invokeArgs($args);
    }
    catch (InvalidConfigurationException $e) {
      throw $e;
    }
    catch (\Exception $e) {
      throw new InvalidConfigurationException('Exception during callback', NULL, $e);
    }
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $callback
   * @param array $args
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public static function callbackAssertValidArgs(CallbackReflectionInterface $callback, array $args) {

    $params = $callback->getReflectionParameters();

    if (array_keys($params) !== array_keys($args)) {
      throw new InvalidConfigurationException('Wrong argument count.');
    }

    foreach ($callback->getReflectionParameters() as $i => $param) {

      if (!array_key_exists($i, $args)) {
        if ($param->isOptional()) {
          // All following parameters are optional.
          return;
        }
        else {
          throw new InvalidConfigurationException("Required argument $i missing.");
        }
      }

      $arg = $args[$i];

      if ($param->isOptional()) {
        if ($args[$i] === $param->getDefaultValue()) {
          continue;
        }
      }

      if ($param->isArray()) {
        if (!is_array($arg)) {
          throw new InvalidConfigurationException("Argument $i must be an array.");
        }
      }

      if ($paramClass = $param->getClass()) {
        if (!is_object($arg)) {
          throw new InvalidConfigurationException("Argument $i must be an object.");
        }
        if (!$paramClass->isInstance($arg)) {
          $paramClassExport = $paramClass->getName();
          throw new InvalidConfigurationException("Argument $i must be an instance of $paramClassExport.");
        }
      }
    }
  }

}
