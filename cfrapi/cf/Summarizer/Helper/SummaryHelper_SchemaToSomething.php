<?php

namespace Donquixote\Cf\Summarizer\Helper;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface;
use Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface;
use Donquixote\Cf\Translator\TranslatorInterface;

class SummaryHelper_SchemaToSomething extends SummaryHelperBase {

  /**
   * @var \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface
   */
  private $schemaToEvaluator;

  /**
   * @param \Donquixote\Cf\SchemaToSomething\SchemaToSomethingInterface $schemaToEvaluator
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(
    SchemaToSomethingInterface $schemaToEvaluator,
    TranslatorInterface $translator
  ) {

    $schemaToEvaluator->requireResultType(
      PartialSummarizerInterface::class);

    $this->schemaToEvaluator = $schemaToEvaluator;

    parent::__construct($translator);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return mixed
   */
  protected function schemaGetPartial(CfSchemaInterface $schema) {
    return $this->schemaToEvaluator->schema($schema);
  }
}
