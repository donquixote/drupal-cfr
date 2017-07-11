<?php

namespace Drupal\cfrapi\PartialD7Formator;

use Donquixote\Cf\Form\D7\Partial\PartialD7Formator_Optionless;
use Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface;
use Drupal\cfrapi\ValueProvider\ValueProviderInterface;

abstract class PartialD7Formator_ValueProvider implements PartialD7FormatorInterface {

  /**
   * @Cf
   *
   * @param \Drupal\cfrapi\ValueProvider\ValueProviderInterface $schema
   *
   * @return \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface
   */
  public static function create(ValueProviderInterface $schema) {
    return new PartialD7Formator_Optionless();
  }
}
