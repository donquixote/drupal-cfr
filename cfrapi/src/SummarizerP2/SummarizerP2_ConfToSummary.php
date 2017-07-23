<?php

namespace Drupal\cfrapi\SummarizerP2;

use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Summarizer\P2\SummarizerP2Interface;
use Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;
use Drupal\cfrapi\SummaryBuilder\SummaryBuilder_Static;

/**
 * @Cf
 */
class SummarizerP2_ConfToSummary implements SummarizerP2Interface {

  /**
   * @var \Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface
   */
  private $schema;

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
