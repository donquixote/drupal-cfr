<?php

namespace Drupal\cfrreflection\CfrGen\CallbackToConfigurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\Context\CfrContextInterface;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface;
use Drupal\cfrapi\Exception\ConfiguratorCreationException;
use Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\cfrreflection\Configurator\Configurator_CallbackInlineable;
use Drupal\cfrreflection\Configurator\Configurator_CallbackSimple;
use Donquixote\Cf\ParamToLabel\ParamToLabelInterface;

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
   * @var \Donquixote\Cf\ParamToLabel\ParamToLabelInterface
   */
  private $paramToLabel;

  /**
   * @param \Drupal\cfrreflection\CfrGen\ParamToConfigurator\ParamToConfiguratorInterface $paramToConfigurator
   * @param \Donquixote\Cf\ParamToLabel\ParamToLabelInterface $paramToLabel
   */
  public function __construct(ParamToConfiguratorInterface $paramToConfigurator, ParamToLabelInterface $paramToLabel) {
    $this->paramToConfigurator = $paramToConfigurator;
    $this->paramToLabel = $paramToLabel;
  }

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $valueCallback
   * @param \Drupal\cfrapi\Context\CfrContextInterface|null $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\ConfiguratorCreationException
   */
  public function callbackGetConfigurator(CallbackReflectionInterface $valueCallback, CfrContextInterface $context = NULL) {

    $params = $valueCallback->getReflectionParameters();

    if (0 === $nParams = count($params)) {
      return new Configurator_CallbackSimple($valueCallback);
    }
    elseif (1 === $nParams) {
      $param = reset($params);
      $argConfigurator = $this->paramToConfigurator->paramGetConfigurator($param, $context);
      $paramLabel = $this->paramToLabel->paramGetLabel($param);
      if ($argConfigurator instanceof InlineableConfiguratorInterface) {
        return new Configurator_CallbackInlineable($valueCallback, $argConfigurator, $paramLabel);
      }
      /** @noinspection MissingOrEmptyGroupStatementInspection */
      elseif ($argConfigurator instanceof ConfiguratorInterface) {
        # return new Configurator_CallbackMono($valueCallback, $argConfigurator);
      }
      /** @noinspection MissingOrEmptyGroupStatementInspection */
      else {
        // Pass through.
      }
    }

    $paramConfigurators = [];
    $paramLabels = [];
    foreach ($params as $param) {
      $paramConfigurators[] = $paramConfigurator = $this->paramToConfigurator->paramGetConfigurator($param, $context);
      if (FALSE === $paramConfigurator || NULL === $paramConfigurator) {
        $paramName = $param->getName();
        throw new ConfiguratorCreationException("No configurator could be constructed for parameter $paramName.");
      }
      $paramLabels[] = $this->paramToLabel->paramGetLabel($param);
    }

    return new Configurator_CallbackConfigurable($valueCallback, $paramConfigurators, $paramLabels);
  }
}
