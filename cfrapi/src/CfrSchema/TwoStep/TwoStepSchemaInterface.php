<?php

namespace Drupal\cfrapi\CfrSchema\TwoStep;

use Drupal\cfrapi\CfrSchema\CfrSchemaInterface;

interface TwoStepSchemaInterface extends CfrSchemaInterface {

  /**
   * @return string
   */
  public function getFirstStepKey();

  /**
   * @return string
   */
  public function getSecondStepKey();

  /**
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface
   */
  public function getFirstStepSchema();

  /**
   * @param mixed $firstStepValue
   *   Value from the first step of configuration.
   *
   * @return \Drupal\cfrapi\CfrSchema\CfrSchemaInterface|null
   *
   * @todo return NULL or throw exception?
   */
  public function firstStepValueGetSecondStepSchema($firstStepValue);

  /**
   * @param mixed $firstStepValue
   *   Value from the first step of configuration.
   * @param mixed $secondStepValue
   *   Value from the second step of configuration.
   *
   * @return mixed
   *   The final value.
   */
  public function valuesGetValue($firstStepValue, $secondStepValue);

}
