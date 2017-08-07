<?php

namespace Donquixote\Cf\ATA\Test;

class B {

  /**
   * @ATA
   *
   * @param \Donquixote\Cf\ATA\Test\A $a
   *
   * @return \Donquixote\Cf\ATA\Test\B
   */
  public static function cast(A $a) {
    return new B();
  }

}
