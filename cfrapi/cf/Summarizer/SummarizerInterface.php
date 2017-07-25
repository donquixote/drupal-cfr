<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Translator\TranslatorInterface;

interface SummarizerInterface {

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return null|string
   */
  public function confGetSummary($conf, TranslatorInterface $translator);

}
