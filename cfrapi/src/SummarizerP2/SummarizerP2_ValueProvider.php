<?php

namespace Drupal\cfrapi\SummarizerP2;

use Donquixote\Cf\Summarizer\P2\SummarizerP2_Null;
use Drupal\cfrapi\Util\UtilBase;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

abstract class SummarizerP2_ValueProvider extends UtilBase {

  /**
   * @Cf
   *
   * @param \Drupal\cfrapi\ValueProvider\ValueProviderInterface $schema
   *
   * @return \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  public static function create(ValueProviderInterface $schema) {
    return new SummarizerP2_Null();
  }
}
