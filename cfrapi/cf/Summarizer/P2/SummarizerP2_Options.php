<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\ConfUtil;

class SummarizerP2_Options implements SummarizerP2Interface {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $schema
   */
  public function __construct(CfSchema_OptionsInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return mixed
   */
  public function confGetSummary($conf, TranslatorInterface $translator) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return $translator->translate('Required id missing.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return $translator->translate(
        'Unknown id "@id" for options schema.',
        ['@id' => $id]);
    }

    return $this->schema->idGetLabel($id);
  }
}
