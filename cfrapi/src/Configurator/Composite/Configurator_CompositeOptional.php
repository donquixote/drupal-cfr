<?php

namespace Drupal\cfrapi\Configurator\Composite;

use Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\ConfToForm\ConfToFormInterface;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;

class Configurator_CompositeOptional extends Configurator_Composite implements OptionalConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Drupal\cfrapi\ConfToForm\ConfToFormInterface $confToForm
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $confToSummary
   * @param \Drupal\cfrapi\ConfToValue\ConfToValueInterface $confToValue
   * @param \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface $emptyness
   */
  function __construct(
    ConfToFormInterface $confToForm,
    ConfToSummaryInterface $confToSummary,
    ConfToValueInterface $confToValue,
    ConfEmptynessInterface $emptyness
  ) {
    parent::__construct($confToForm, $confToSummary, $confToValue);
    $this->emptyness = $emptyness;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness() {
    return $this->emptyness;
  }
}
