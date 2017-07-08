<?php

namespace Donquixote\Cf\Schema\Options;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

abstract class CfSchema_Options_PassthruBase implements CfSchema_OptionsInterface {

  /**
   * @param string|int $id
   *
   * @return string|int
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  final public function idGetValue($id) {
    return $id;
  }

  /**
   * @param string|int $id
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function idGetPhp($id, CfrCodegenHelperInterface $helper) {
    return var_export($id, TRUE);
  }
}
