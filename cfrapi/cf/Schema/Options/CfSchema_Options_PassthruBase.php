<?php

namespace Donquixote\Cf\Schema\Options;

abstract class CfSchema_Options_PassthruBase implements CfSchema_OptionsInterface {

  /**
   * @param string|int $id
   *
   * @return string|int
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  final public function idGetValue($id) {
    return $id;
  }

  /**
   * @param string|int $id
   *
   * @return string
   */
  public function idGetPhp($id) {
    return var_export($id, TRUE);
  }
}
