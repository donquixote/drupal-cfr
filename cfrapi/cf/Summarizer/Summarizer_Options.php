<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\ConfUtil;

/**
 * @Cf
 */
class Summarizer_Options implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\Translator\TranslatorInterface
   */
  private $translator;

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $schema
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   */
  public function __construct(CfSchema_OptionsInterface $schema, TranslatorInterface $translator) {
    $this->schema = $schema;
    $this->translator = $translator;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetSummary($conf) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return $this->translator->translate('Required id missing.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return $this->translator->translate(
        'Unknown id "@id" for options schema.',
        ['@id' => $id]);
    }

    return $this->schema->idGetLabel($id);
  }
}
