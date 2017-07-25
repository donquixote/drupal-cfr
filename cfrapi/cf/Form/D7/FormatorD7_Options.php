<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\SchemaBase\Options\CfSchemaBase_AbstractOptionsInterface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Form\D7\Util\D7FormUtil;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Util\ConfUtil;

class FormatorD7_Options implements FormatorD7Interface, OptionableFormatorD7Interface {

  /**
   * @var \Donquixote\Cf\SchemaBase\Options\CfSchemaBase_AbstractOptionsInterface
   */
  private $schema;

  /**
   * @var bool
   */
  private $required = TRUE;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $schema
   *
   * @return self
   */
  public static function create(CfSchema_OptionsInterface $schema) {
    return new self($schema);
  }

  /**
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public function getOptionalFormator() {

    if (!$this->required) {
      return NULL;
    }

    $clone = clone $this;
    $clone->required = FALSE;
    return $clone;
  }

  /**
   * @param \Donquixote\Cf\SchemaBase\Options\CfSchemaBase_AbstractOptionsInterface $schema
   */
  public function __construct(CfSchemaBase_AbstractOptionsInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return mixed
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {

    return D7FormUtil::optionsSchemaBuildSelectElement(
      $this->schema,
      ConfUtil::confGetId($conf),
      $label,
      $this->required);
  }
}
