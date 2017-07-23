<?php

namespace Donquixote\Cf\Form\D7\P2;

use Donquixote\Cf\Form\D7\P2\Optionable\OptionableD7FormatorP2Interface;
use Donquixote\Cf\Translator\TranslatorInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;

class D7FormatorP2_Optionless implements D7FormatorP2Interface, OptionableD7FormatorP2Interface {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return self
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ CfSchema_OptionlessInterface $schema
  ) {
    return new self();
  }

  /**
   * @return \Donquixote\Cf\Form\D7\P2\D7FormatorP2Interface|null
   */
  public function getOptionalFormator() {
    return new D7FormatorP2_Bool();
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Translator\TranslatorInterface $translator
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, TranslatorInterface $translator) {
    return [];
  }
}
