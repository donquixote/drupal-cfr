<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Translator\TranslatorInterface;

interface SummarizerP2Interface {

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return null|string
   */
  public function confGetSummary($conf, TranslatorInterface $translator);

}
