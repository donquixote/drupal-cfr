<?php

namespace Drupal\cfrreflection\Configurator;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Drupal\cfrapi\BrokenValue\BrokenValue;
use Drupal\cfrapi\BrokenValue\BrokenValueInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;
use Drupal\cfrfamily\CfrLegendProvider\CfrLegendProviderInterface;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorBase;
use Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface;

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
   * @var null|string
   */
  private $paramLabel;

  /**
   * @param \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface $monoParamCallback
   *   Callback with exactly one parameter.
   * @param \Drupal\cfrfamily\Configurator\Inlineable\InlineableConfiguratorInterface $argConfigurator
   * @param string|null $paramLabel
   */
  public function __construct(CallbackReflectionInterface $monoParamCallback, InlineableConfiguratorInterface $argConfigurator, $paramLabel = NULL) {
    $this->callback = $monoParamCallback;
    $this->argConfigurator = $argConfigurator;
    $this->paramLabel = $paramLabel;
  }

  /**
   * @return \Drupal\cfrfamily\CfrLegend\CfrLegendInterface|null
   */
  public function getCfrLegend() {
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
  public function confGetForm($conf, $label) {

    if (NULL === $label) {
      $label = $this->paramLabel;
    }
    elseif (NULL !== $this->paramLabel) {
      $label .= ' | ' . $this->paramLabel;
    }

    return $this->argConfigurator->confGetForm($conf, $label);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->argConfigurator->confGetSummary($conf, $summaryBuilder);
  }

  /**
   * @param string $id
   * @param mixed $optionsConf
   *
   * @return mixed
   */
  public function idConfGetValue($id, $optionsConf) {
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
