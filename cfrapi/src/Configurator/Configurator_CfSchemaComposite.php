<?php

namespace Drupal\cfrapi\Configurator;

use Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface;
use Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_Optional_Null;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Summarizer\P2\SummarizerP2Interface;
use Donquixote\Cf\Translator\Lookup\TranslatorLookup_D7;
use Donquixote\Cf\Translator\Translator;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\StaUtil;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_FromCfEmptyness;
use Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface;
use Drupal\cfrapi\Configurator\Optionable\OptionableConfigurator_Fixed;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilderInterface;

class Configurator_CfSchemaComposite implements OptionalConfiguratorInterface {

  /**
   * @var \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface
   */
  private $formator;

  /**
   * @var \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  private $summarizer;

  /**
   * @var \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface
   */
  private $evaluator;

  /**
   * @var \Donquixote\Cf\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @var \Donquixote\Cf\Emptyness\EmptynessInterface|null
   */
  private $emptyness;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    if (0
      || NULL === ($formator = StaUtil::formatorP2($schema, $schemaToAnything))
      || NULL === ($summarizer = StaUtil::summarizerP2($schema, $schemaToAnything))
      || NULL === ($evaluator = StaUtil::evaluatorP2($schema, $schemaToAnything))
    ) {
      kdpm(get_defined_vars(), __METHOD__);

      return NULL;
    }

    $lookup = TranslatorLookup_D7::createOrPassthru();
    $translator = new Translator($lookup);

    return new self($formator, $summarizer, $evaluator, $translator);
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface|null
   */
  public static function createOptionable(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    $optionalSchema = new CfSchema_Optional_Null($schema);

    if (0
      || NULL === ($emptiness = StaUtil::emptyness($schema, $schemaToAnything))
      || NULL === ($formator = StaUtil::formatorP2($optionalSchema, $schemaToAnything))
      || NULL === ($summarizer = StaUtil::summarizerP2($optionalSchema, $schemaToAnything))
      || NULL === ($evaluator = StaUtil::evaluatorP2($optionalSchema, $schemaToAnything))
    ) {
      kdpm(get_defined_vars(), __METHOD__);

      return NULL;
    }


    $lookup = TranslatorLookup_D7::createOrPassthru();
    $translator = new Translator($lookup);

    $confEmptiness = new ConfEmptyness_FromCfEmptyness($emptiness);

    $configurator = new self(
      $formator,
      $summarizer,
      $evaluator,
      $translator,
      $confEmptiness);

    return new OptionableConfigurator_Fixed($configurator);
  }

  /**
   * @param \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface $formator
   * @param \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface $summarizer
   * @param \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface $evaluator
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   * @param \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface|null $emptyness
   */
  public function __construct(
    D7FormatorP2Interface $formator,
    SummarizerP2Interface $summarizer,
    EvaluatorP2Interface $evaluator,
    TranslatorInterface $translator,
    ConfEmptynessInterface $emptyness = NULL
  ) {
    $this->formator = $formator;
    $this->summarizer = $summarizer;
    $this->evaluator = $evaluator;
    $this->translator = $translator;
    $this->emptyness = $emptyness;
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

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label) {
    return $this->formator->confGetD7Form($conf, $label, $this->translator);
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
    return $this->summarizer->confGetSummary($conf, $this->translator);
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
    return $this->evaluator->confGetValue($conf);
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
    return $this->evaluator->confGetPhp($conf);
  }
}
