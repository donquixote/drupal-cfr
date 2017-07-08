<?php

namespace Donquixote\Cf\Schema\Neutral;

use Donquixote\Cf\Schema\Transformable\CfSchema_TransformableInterface;

interface CfSchema_NeutralInterface extends CfSchema_TransformableInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getDecorated();

}
