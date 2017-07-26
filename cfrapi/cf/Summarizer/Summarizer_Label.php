<?php

namespace Donquixote\Cf\Summarizer;

use Donquixote\Cf\Schema\Label\CfSchema_LabelInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\HtmlUtil;
use Donquixote\Cf\Util\StaUtil;

class Summarizer_Label implements SummarizerInterface {

  /**
   * @var \Donquixote\Cf\Summarizer\SummarizerInterface
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

    if (NULL === $decorated = StaUtil::summarizer(
        $schema->getDecorated(),
        $schemaToAnything)
    ) {
      return NULL;
    }

    return new self($decorated, $schema->getLabel());
  }

  /**
   * @param \Donquixote\Cf\Summarizer\SummarizerInterface $decorated
   * @param string $label
   */
  public function __construct(SummarizerInterface $decorated, $label) {
    $this->decorated = $decorated;
    $this->label = $label;
  }

  /**
   * @param mixed $conf
   *
   * @return null|string
   */
  public function confGetSummary($conf) {

    $decorated = $this->decorated->confGetSummary($conf);

    if ('' === $decorated || NULL === $decorated) {
      return $decorated;
    }

    $label = HtmlUtil::sanitize($this->label);

    return $label . ': ' . $decorated;
  }
}
