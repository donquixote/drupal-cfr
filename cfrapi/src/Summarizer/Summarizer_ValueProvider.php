<?php

namespace Drupal\cfrapi\Summarizer;

use Donquixote\Cf\Summarizer\Summarizer_Null;
use Drupal\cfrapi\Util\UtilBase;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

abstract class Summarizer_ValueProvider extends UtilBase {

  /**
   * @Cf
   *
   * @param \Drupal\cfrapi\ValueProvider\ValueProviderInterface $schema
   *
   * @return \Donquixote\Cf\Summarizer\SummarizerInterface
   */
  public static function create(ValueProviderInterface $schema) {
    return new Summarizer_Null();
  }
}
