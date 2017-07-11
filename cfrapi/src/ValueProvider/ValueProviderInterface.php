<?php

namespace Drupal\cfrapi\ValueProvider;

use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

interface ValueProviderInterface extends CfSchema_OptionlessInterface {

  /**
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function getValue();

  /**
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp(CfrCodegenHelperInterface $helper);
}
