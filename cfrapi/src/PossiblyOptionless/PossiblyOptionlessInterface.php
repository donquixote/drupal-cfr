<?php

namespace Drupal\cfrapi\PossiblyOptionless;

use Donquixote\Cf\Optionlessness\OptionlessnessInterface;

interface PossiblyOptionlessInterface extends OptionlessnessInterface {

  /**
   * @return bool
   */
  public function isOptionless();

}
