<?php

namespace Drupal\cfrapi\SummarizerP2;

use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_CallbackNoHelper;
use Donquixote\Cf\Summarizer\P2\SummarizerP2Interface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilder_Static;

class SummarizerP2_ConfToSummary implements SummarizerP2Interface {

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
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return null|string
   */
  public function confGetSummary($conf, TranslatorInterface $translator) {

    return $this->schema->confGetSummary(
      $conf,
      new SummaryBuilder_Static());
  }
}
