<?php

namespace Donquixote\Cf\Schema\Optionless;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

class CfSchema_Optionless_Null implements CfSchema_OptionlessInterface {

  /**
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function getValue() {
    return NULL;
  }

  /**
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(CfrCodegenHelperInterface $helper) {
    return 'NULL';
  }
}
