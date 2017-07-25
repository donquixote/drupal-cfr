<?php

namespace Donquixote\Cf\Summarizer;

class Summarizer_Null implements SummarizerInterface {

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetSummary($conf) {
    return NULL;
  }
}
