<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;

class PartialD7Formator_Optionless implements PartialD7FormatorInterface {

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
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {
    return [];
  }
}
