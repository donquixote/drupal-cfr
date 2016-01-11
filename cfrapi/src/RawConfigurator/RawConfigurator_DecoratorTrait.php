<?php

namespace Drupal\cfrapi\RawConfigurator;

use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

trait RawConfigurator_DecoratorTrait {

  /**
   * @var \Drupal\cfrapi\RawConfigurator\RawConfiguratorInterface
   */
  private $decorated;

  /**
   * @param \Drupal\cfrapi\RawConfigurator\RawConfiguratorInterface $decorated
   */
  function __construct(RawConfiguratorInterface $decorated) {
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
