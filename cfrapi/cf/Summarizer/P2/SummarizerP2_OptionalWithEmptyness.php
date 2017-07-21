<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\HtmlUtil;

class SummarizerP2_OptionalWithEmptyness implements SummarizerP2Interface {

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  private $emptyness;

  /**
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface $decorated
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $emptyness
   */
  public function __construct(
    CfSchema_OptionalInterface $schema,
    SummarizerP2Interface $decorated,
    EmptynessInterface $emptyness
  ) {
    $this->schema = $schema;
    $this->decorated = $decorated;
    $this->emptyness = $emptyness;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return null|string
   */
  public function confGetSummary($conf, TranslatorInterface $translator) {

    if ($this->emptyness->confIsEmpty($conf)) {

      if (NULL === $summaryUnsafe = $this->schema->getEmptySummary()) {
        return NULL;
      }

      // The schema's summary might not be designed for HTML.
      return HtmlUtil::sanitize($summaryUnsafe);
    }

    return $this->decorated->confGetSummary($conf, $translator);
  }
}
