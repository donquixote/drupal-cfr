<?php

namespace Donquixote\Cf\V2V\Value;

interface V2V_ValueInterface {

  /**
   * @param mixed $value
   *
   * @return mixed
   */
  public function valueGetValue($value);

  /**
   * @param string $php
   *
   * @return string
   */
  public function phpGetPhp($php);

}
