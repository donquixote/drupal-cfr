<?php

namespace Drupal\cfrreflection\Configurator;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorBase;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class Configurator_CallbackInlineable extends InlineableConfiguratorBase {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @var \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface
   */
  private $argConfigurator;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $monoParamCallback
   *   Callback with exactly one parameter.
   * @param \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface $argConfigurator
   */
  function __construct(CallbackReflectionInterface $monoParamCallback, InlineableConfiguratorInterface $argConfigurator) {
    $this->callback = $monoParamCallback;
    $this->argConfigurator = $argConfigurator;
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface|null
   */
  function getCfrLegend() {
    if (!$this->argConfigurator instanceof CfrLegendProviderInterface) {
      return NULL;
    }
    return $this->argConfigurator->getCfrLegend();
  }

  /**
   * @param array $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetForm($conf, $label) {
    return $this->argConfigurator->confGetForm($conf, $label);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->argConfigurator->confGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  function idConfGetValue($id, $optionsConf) {
    $arg = $this->argConfigurator->idConfGetValue($id, $optionsConf);
    if ($arg instanceof BrokenValueInterface) {
      return $arg;
    }
    // @todo Validate $arg.
    try {
      return $this->callback->invokeArgs([$arg]);
    }
    catch (\Exception $e) {
      return new BrokenValue($this, get_defined_vars(), 'Exception during callback.');
    }
  }
}
