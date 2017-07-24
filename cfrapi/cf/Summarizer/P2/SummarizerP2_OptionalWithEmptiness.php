<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Emptiness\EmptinessInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\HtmlUtil;

class SummarizerP2_OptionalWithEmptiness implements SummarizerP2Interface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  private $emptiness;

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface $decorated
   * @param \Donquixote\Cf\Emptiness\EmptinessInterface $emptiness
   */
  public function __construct(
    CfSchema_OptionalInterface $schema,
    SummarizerP2Interface $decorated,
    EmptinessInterface $emptiness
  ) {
    $this->schema = $schema;
    $this->decorated = $decorated;
    $this->emptiness = $emptiness;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return null|string
   */
  public function confGetSummary($conf, TranslatorInterface $translator) {

    if ($this->emptiness->confIsEmpty($conf)) {

      if (NULL === $summaryUnsafe = $this->schema->getEmptySummary()) {
        return NULL;
      }

      // The schema's summary might not be designed for HTML.
      return HtmlUtil::sanitize($summaryUnsafe);
    }

    return $this->decorated->confGetSummary($conf, $translator);
  }
}
