<?php

namespace Drupal\cfrapi\Formator;

use Donquixote\Cf\Form\D7\FormatorD7_Optionless;
use Drupal\cfrapi\Util\UtilBase;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

final class Formator_ValueProvider extends UtilBase {

  /**
   * @Cf
   *
   * @param \Drupal\cfrapi\ValueProvider\ValueProviderInterface $schema
   *
   * @return \Donquixote\Cf\Form\D7\FormatorD7_Optionless
   */
  public static function create(
    /** @noinspection PhpUnusedParameterInspection */ ValueProviderInterface $schema) {

    return new FormatorD7_Optionless();
  }
}