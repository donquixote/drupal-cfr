<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Schema\Label\CfSchema_LabelInterface;
use Donquixote\Cf\Util\StaUtil;

class D7FormatorP2_Label implements D7FormatorP2Interface {

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

    if (NULL === $decorated = StaUtil::formatorP2(
      $schema->getDecorated(),
      $schemaToAnything)
    ) {
      return NULL;
    }

    return new self($decorated, $schema->getLabel());
  }

  /**
   * @param \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface $decorated
   * @param string $label
   */
  public function __construct(D7FormatorP2Interface $decorated, $label) {
    $this->decorated = $decorated;
    $this->label = $label;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {

    return $this->decorated->confGetD7Form($conf, $this->label, $translator);
  }
}
