<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Drupal\cfrapi\Configurator\Broken\BrokenConfigurator;
use Drupal\cfrapi\Configurator\Group\Configurator_Group;
use Drupal\cfrapi\Configurator\Group\GroupConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface;
use Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\cfrreflection\Configurator\Configurator_CallbackInlineable;
use Drupal\cfrreflection\Configurator\Configurator_CallbackSimple;
use Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

/**
 * Creates a configurator for a callback, where the callback return value is the
 * business value, and the callback parameters represent configuration options.
 *
 * Parameter configurators are auto-generated based on the type hint.
 */
class CallbackToConfigurator_ValueCallback implements CallbackToConfiguratorInterface {

  /**
   * @var \Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface
   */
  private $paramToConfigurator;

  /**
   * @var \Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * @param \Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface $paramToConfigurator
   * @param \Drupal\cfrreflection\ParamToLabel\ParamToLabelInterface $paramToLabel
   */
  function __construct(ParamToConfiguratorInterface $paramToConfigurator, ParamToLabelInterface $paramToLabel) {
    $this->paramToConfigurator = $paramToConfigurator;
    $this->paramToLabel = $paramToLabel;
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $valueCallback
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  function callbackGetConfigurator(CallbackReflectionInterface $valueCallback, CfrContextInterface $context = NULL) {

    $params = $valueCallback->getReflectionParameters();
    if (0 === $nParams = count($params)) {
      return new Configurator_CallbackSimple($valueCallback);
    }
    elseif (1 === $nParams) {
      $argConfigurator = $this->paramToConfigurator->paramGetConfigurator(reset($params), $context);
      if ($argConfigurator instanceof InlineableConfiguratorInterface) {
        return new Configurator_CallbackInlineable($valueCallback, $argConfigurator);
      }
      else {

      }
    }

    $argsConfigurator = $this->paramsGetConfigurator($params, $context);

    if (!$argsConfigurator instanceof GroupConfiguratorInterface) {
      return new BrokenConfigurator($this, get_defined_vars(), 'Unable to build configurators for all arguments.');
    }

    return new Configurator_CallbackConfigurable($valueCallback, $argsConfigurator);
  }

  /**
   * @param \ReflectionParameter[] $params
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private function paramsGetConfigurator(array $params, CfrContextInterface $context = NULL) {
    $groupConfigurator = new Configurator_Group();
    foreach ($params as $i => $param) {
      $paramConfigurator = $this->paramToConfigurator->paramGetConfigurator($param, $context);
      if (FALSE === $paramConfigurator || NULL === $paramConfigurator) {
        return FALSE;
      }
      $paramLabel = $this->paramToLabel->paramGetLabel($param);
      $groupConfigurator->keySetConfigurator($i, $paramConfigurator, $paramLabel);
    }
    return $groupConfigurator;
  }
}
