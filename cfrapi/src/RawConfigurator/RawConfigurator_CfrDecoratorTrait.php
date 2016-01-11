<?php

namespace Drupal\cfrapi\RawConfigurator;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

/**
 * Base class for classes that want to decorate a Configurator, but do not want
 * to implement confGetValue().
 */
trait RawConfigurator_CfrDecoratorTrait {

  /**
   * @var \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  private $decorated;

  /**
   * @param \Drupal\cfrapi\Configurator\ConfiguratorInterface $decorated
   */
  function __construct(ConfiguratorInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetForm($conf, $label) {
    return $this->decorated->confGetForm($conf, $label);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *
   * @return null|string
   */
  function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->decorated->confGetSummary($conf, $summaryBuilder);
  }

}
