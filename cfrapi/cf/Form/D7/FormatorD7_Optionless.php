<?php

namespace Donquixote\Cf\Form\D7;

use Donquixote\Cf\Form\D7\Optionable\OptionableFormatorD7Interface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;

class FormatorD7_Optionless implements FormatorD7Interface, OptionableFormatorD7Interface {

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
   * @return \Donquixote\Cf\Form\D7\FormatorD7Interface|null
   */
  public function getOptionalFormator() {
    return new FormatorD7_Bool();
  }

  /**
   * @param mixed $conf
   * @param string $label
   *
   * @return array
   */
  public function confGetD7Form($conf, $label) {
    return [];
  }
}
