<?php

namespace Donquixote\Cf\Schema\TwoStep;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;

interface CfSchema_TwoStepInterface extends CfSchemaLocalInterface {

  /**
   * @return string
   */
  public function getFirstStepKey();

  /**
   * @return string
   */
  public function getSecondStepKey();

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getFirstStepSchema();

  /**
   * @param mixed $firstStepValue
   *   Value from the first step of configuration.
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
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
