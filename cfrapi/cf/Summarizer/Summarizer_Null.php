<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Translator\TranslatorInterface;

class Summarizer_Null implements SummarizerInterface {

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return mixed
   */
  public function confGetSummary($conf, TranslatorInterface $translator) {
    return NULL;
  }
}
