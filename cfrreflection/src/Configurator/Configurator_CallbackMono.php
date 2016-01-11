<?php

namespace Drupal\cfrreflection\Configurator;

use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;

class Configurator_CallbackMono implements ConfiguratorInterface {

  /**
   * @var \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  private $callback;

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $argConfigurator;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $monoParamCallback
   *   Callback with exactly one parameter.
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $argConfigurator
   */
  function __construct(CallbackReflectionInterface $monoParamCallback, ConfiguratorInterface $argConfigurator) {
    $this->callback = $monoParamCallback;
    $this->argConfigurator = $argConfigurator;
  }

  /**
   * @return \Drupal\cfrapi\StructuredLegend\StructuredLegendInterface|null
   */
  function getStructuredLegend() {
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
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   */
  function confGetValue($conf) {
    $arg = $this->argConfigurator->confGetValue($conf);
    if ($arg instanceof BrokenValueInterface) {
      return $arg;
    }
    // @todo Validate $arg.
    try {
      return $this->callback->invokeArgs(array($arg));
    }
    catch (\Exception $e) {
      return new BrokenValue($this, get_defined_vars(), 'Exception during callback.');
    }
  }
}
