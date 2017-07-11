<?php

namespace Donquixote\Cf\Schema\Callback;

use Donquixote\Cf\Schema\CfSchemaLocalInterface;

interface CfSchema_CallbackInterface extends CfSchemaLocalInterface {

  /**
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface
   */
  public function getCallback();

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface[]
   */
  public function getExplicitParamSchemas();

  /**
   * @return string[]
   */
  public function getExplicitParamLabels();

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext();

}
