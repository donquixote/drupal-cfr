<?php

namespace Donquixote\Cf\ParamToLabel;

use Donquixote\Cf\Util\StringUtil;

class ParamToLabel implements ParamToLabelInterface {

  /**
   * @param \ReflectionParameter $param
   *
   * @return string|null
   */
  public function paramGetLabel(\ReflectionParameter $param) {
    return StringUtil::methodNameGenerateLabel($param->getName());
  }
}
