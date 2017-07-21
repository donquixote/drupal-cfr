<?php

namespace Donquixote\Cf\Form\D7\Optional;

interface PartialD7FormatorOptionalInterface {

  /**
   * @return \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface
   */
  public function getFormator();

  /**
   * @return \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  public function getEmptyness();

}
