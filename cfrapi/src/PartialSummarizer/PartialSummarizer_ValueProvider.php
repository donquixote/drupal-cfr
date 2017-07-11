<?php

namespace Drupal\cfrapi\PartialSummarizer;

use Donquixote\Cf\Summarizer\Partial\PartialSummarizer_Null;
use Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

abstract class PartialSummarizer_ValueProvider implements PartialSummarizerInterface {

  /**
   * @Cf
   *
   * @param \Drupal\cfrapi\ValueProvider\ValueProviderInterface $schema
   *
   * @return \Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface
   */
  public static function create(ValueProviderInterface $schema) {
    return new PartialSummarizer_Null();
  }
}
