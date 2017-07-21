<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Translator\TranslatorInterface;

class SummarizerP2_Null implements SummarizerP2Interface {

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
