<?php

namespace Drupal\cfrapi\SchemaToConfigurator;

use Donquixote\Cf\Emptyness\Emptyness_Key;
use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional_Null;
use Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_FromCfEmptyness;
use Drupal\cfrapi\Configurator\Configurator_CfSchema;
use Drupal\cfrapi\Configurator\Configurator_CfSchemaOptional;

class SchemaToConfigurator_Helpers implements SchemaToConfiguratorInterface {

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
   * @var \Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface
   */
  private $schemaToEmptyness;

  /**
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $valueHelper
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $phpHelper
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $formHelper
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $summaryHelper
   * @param \Donquixote\Cf\SchemaToEmptyness\SchemaToEmptynessInterface $schemaToEmptyness
   */
  public function __construct(
    ConfToValueHelperInterface $valueHelper,
    ConfToPhpHelperInterface $phpHelper,
    D7FormatorHelperInterface $formHelper,
    SummaryHelperInterface $summaryHelper,
    SchemaToEmptynessInterface $schemaToEmptyness
  ) {
    $this->valueHelper = $valueHelper;
    $this->phpHelper = $phpHelper;
    $this->formHelper = $formHelper;
    $this->summaryHelper = $summaryHelper;
    $this->schemaToEmptyness = $schemaToEmptyness;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetConfigurator(CfSchemaInterface $schema) {

    return new Configurator_CfSchema(
      $schema,
      $this->valueHelper,
      $this->phpHelper,
      $this->formHelper,
      $this->summaryHelper);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface
   *
   * @throws \Drupal\cfrapi\Exception\UnsupportedSchemaException
   */
  public function schemaGetOptionalConfigurator(CfSchemaInterface $schema) {

    // @todo This is stupid.
    $optionalSchema = new CfSchema_Optional_Null($schema);

    $emptyness = $this->schemaToEmptyness->schemaGetEmptyness($schema);

    if (NULL === $emptyness) {
      $emptyness = new Emptyness_Key('enabled');
    }

    $confEmptyness = new ConfEmptyness_FromCfEmptyness($emptyness);

    return new Configurator_CfSchemaOptional(
      $optionalSchema,
      $this->valueHelper,
      $this->phpHelper,
      $this->formHelper,
      $this->summaryHelper,
      $confEmptyness);
  }
}
