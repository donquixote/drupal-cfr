<?php

namespace Donquixote\Cf\Schema\Sequence;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\SchemaBase\CfSchema_TransformableInterface;

interface CfSchema_SequenceInterface extends CfSchema_TransformableInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getItemSchema();

  /**
   * @param int $delta
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return string
   */
  public function deltaGetItemLabel($delta, D7FormatorHelperInterface $helper);

}
