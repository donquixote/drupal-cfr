<?php

namespace Drupal\cfrapi\FormatorP2;

use Donquixote\Cf\Form\D7\P2\D7FormatorP2_Optionless;
use Drupal\cfrapi\Util\UtilBase;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

final class FormatorP2_ValueProvider extends UtilBase {

  /**
   * @Cf
   *
   * @param \Drupal\cfrapi\ValueProvider\ValueProviderInterface $schema
   *
   * @return \Donquixote\Cf\Form\D7\P2\D7FormatorP2_Optionless
   */
  public static function create(ValueProviderInterface $schema) {
    return new D7FormatorP2_Optionless();
  }
}
