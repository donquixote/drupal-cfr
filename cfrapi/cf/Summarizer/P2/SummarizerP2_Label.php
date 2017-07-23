<?php

namespace Donquixote\Cf\Summarizer\P2;

use Donquixote\Cf\Schema\Label\CfSchema_LabelInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Util\StaUtil;

class SummarizerP2_Label implements SummarizerP2Interface {

  /**
   * @var \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface
   */
  private $decorated;

  /**
   * @var string
   */
  private $label;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Label\CfSchema_LabelInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(CfSchema_LabelInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    if (NULL === $decorated = StaUtil::summarizerP2(
        $schema->getDecorated(),
        $schemaToAnything)
    ) {
      return NULL;
    }

    return new self($decorated, $schema->getLabel());
  }

  /**
   * @param \Donquixote\Cf\Summarizer\P2\SummarizerP2Interface $decorated
   * @param string $label
   */
  public function __construct(SummarizerP2Interface $decorated, $label) {
    $this->decorated = $decorated;
    $this->label = $label;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return null|string
   */
  public function confGetSummary($conf, TranslatorInterface $translator) {

    $decorated = $this->decorated->confGetSummary($conf, $translator);

    if ('' === $decorated || NULL === $decorated) {
      return $decorated;
    }

    $label = HtmlUtil::sanitize($this->label);

    return $label . ': ' . $decorated;
  }
}
