<?php

namespace Drupal\cfrapi\ValueProvider;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface ValueProviderInterface extends CfrSchemaInterface {

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
