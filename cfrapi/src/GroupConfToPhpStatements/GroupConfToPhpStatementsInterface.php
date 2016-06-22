<?php

namespace Drupal\cfrapi\GroupConfToPhpStatements;

interface GroupConfToPhpStatementsInterface {

  /**
   * @param mixed $conf
   *
   * @return string[]
   *   PHP statements to generate the values.
   *
   * @throws \Drupal\cfrapi\Exception\PhpGenerationNotSupportedException
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function confGetPhpStatements($conf);

}
