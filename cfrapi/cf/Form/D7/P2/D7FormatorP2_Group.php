<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Util\StaUtil;

class D7FormatorP2_Group implements D7FormatorP2Interface {

  /**
   * @var \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface[]
   */
  private $itemFormators;

  /**
   * @var string[]
   */
  private $labels;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\P2\D7FormatorP2_Group|null
   */
  public static function create(CfSchema_GroupInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    if (NULL === $itemFormators = StaUtil::getMultiple(
      $schema->getItemSchemas(),
      $schemaToAnything,
      D7FormatorP2Interface::class)
    ) {
      return NULL;
    }

    return new self($itemFormators, $schema->getLabels());
  }

  /**
   * @param \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface[] $itemFormators
   * @param string[] $labels
   */
  public function __construct(array $itemFormators, array $labels) {
    $this->itemFormators = $itemFormators;
    $this->labels = $labels;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {

    if (!is_array($conf)) {
      $conf = [];
    }

    $form = [];

    if (NULL !== $label && '' !== $label) {
      $form['#title'] = $label;
    }

    foreach ($this->itemFormators as $key => $itemFormator) {

      $itemConf = isset($conf[$key])
        ? $conf[$key]
        : NULL;

      $itemLabel = isset($this->labels[$key])
        ? $this->labels[$key]
        : $key;

      $form[$key] = $itemFormator->confGetD7Form($itemConf, $itemLabel, $translator);
    }

    return $form;
  }
}
