<?php

namespace Drupal\cfrapi\GroupConfToPhpStatements;

use Drupal\cfrapi\CodegenHelper\CodegenHelperInterface;

interface GroupConfToPhpStatementsInterface {

  /**
   * @param mixed $conf
   * @param \Drupal\cfrapi\CodegenHelper\CodegenHelperInterface $helper
   *
   * @return string[]
   *   PHP statements to generate the values.
   */
  public function confGetPhpStatements($conf, CodegenHelperInterface $helper);

}
