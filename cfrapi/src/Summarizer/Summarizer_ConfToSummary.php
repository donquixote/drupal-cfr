<?php

namespace Drupal\cfrapi\Summarizer;

use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_CallbackNoHelper;
use Donquixote\Cf\Summarizer\SummarizerInterface;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilder_Static;

class Summarizer_ConfToSummary implements SummarizerInterface {

  /**
   * @var \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface
   */
  private $schema;

  /**
   * @Cf
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface
   */
  public static function sta() {

    return SchemaToAnythingPartial_CallbackNoHelper::fromClassName(
      __CLASS__,
      ConfToSummaryInterface::class);
  }

  /**
   * @param \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface $schema
   */
  public function __construct(ConfToSummaryInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   *
   * @return null|string
   */
  public function confGetSummary($conf) {

    return $this->schema->confGetSummary(
      $conf,
      new SummaryBuilder_Static());
  }
}
