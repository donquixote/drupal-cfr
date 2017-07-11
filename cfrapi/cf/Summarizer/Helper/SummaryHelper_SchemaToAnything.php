<?php

namespace Donquixote\Cf\Summarizer\Helper;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface;
use Donquixote\Cf\Translator\TranslatorInterface;

class SummaryHelper_SchemaToAnything extends SummaryHelperBase {

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(
    SchemaToAnythingInterface $schemaToAnything,
    TranslatorInterface $translator
  ) {
    $this->schemaToAnything = $schemaToAnything;
    parent::__construct($translator);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return mixed
   */
  protected function schemaGetPartial(CfSchemaInterface $schema) {

    return $this->schemaToAnything->schema(
      $schema,
      PartialSummarizerInterface::class);
  }
}
