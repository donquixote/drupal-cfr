<?php

namespace Drupal\cfrapi\GroupConfToPhpStatements;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

interface GroupConfToPhpStatementsInterface {

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string[]
   *   PHP statements to generate the values.
   */
  public function confGetPhpStatements($conf, CfrCodegenHelperInterface $helper);

}
