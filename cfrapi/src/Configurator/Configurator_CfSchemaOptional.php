<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_CfSchemaOptional implements OptionalConfiguratorInterface {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface
   */
  private $valueHelper;

  /**
   * @var \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface
   */
  private $phpHelper;

  /**
   * @var \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface
   */
  private $formHelper;

  /**
   * @var \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface
   */
  private $summaryHelper;

  /**
   * @var \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $valueHelper
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $phpHelper
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $formHelper
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $summaryHelper
   * @param \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface $emptyness
   */
  public function __construct(
    CfSchemaInterface $schema,
    ConfToValueHelperInterface $valueHelper,
    ConfToPhpHelperInterface $phpHelper,
    D7FormatorHelperInterface $formHelper,
    SummaryHelperInterface $summaryHelper,
    ConfEmptynessInterface $emptyness
  ) {
    $this->schema = $schema;
    $this->valueHelper = $valueHelper;
    $this->phpHelper = $phpHelper;
    $this->formHelper = $formHelper;
    $this->summaryHelper = $summaryHelper;
    $this->emptyness = $emptyness;
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    return $this->formHelper->schemaConfGetD7Form($this->schema, $conf, $label);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface $summaryBuilder
   *   An object that controls the format of the summary.
   *
   * @return mixed|string|null
   *   A string summary is always allowed. But other values may be returned if
   *   $summaryBuilder generates them.
   */
  public function confGetSummary($conf, SummaryBuilderInterface $summaryBuilder) {
    return $this->summaryHelper->schemaConfGetSummary($this->schema, $conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   *
   * @return mixed
   *   Value to be used in the application.
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function confGetValue($conf) {
    return $this->valueHelper->schemaConfGetValue($this->schema, $conf);
  }

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function confGetPhp($conf, CfrCodegenHelperInterface $helper) {
    return $this->phpHelper->schemaConfGetPhp($this->schema, $conf);
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null
   *   An emptyness object, or
   *   NULL, if the configurator is in fact required and thus no valid conf
   *   counts as empty.
   */
  public function getEmptyness() {
    return $this->emptyness;
  }
}
