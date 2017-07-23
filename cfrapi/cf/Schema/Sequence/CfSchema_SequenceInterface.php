<?php

namespace Donquixote\Cf\Schema\Sequence;

use Donquixote\Cf\SchemaBase\CfSchema_TransformableInterface;
use Donquixote\Cf\Translator\TranslatorInterface;

interface CfSchema_SequenceInterface extends CfSchema_TransformableInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getItemSchema();

  /**
   * @param int $delta
   * @param \Donquixote\Cf\Translator\TranslatorInterface $helper
   *
   * @return string
   */
  public function deltaGetItemLabel($delta, TranslatorInterface $helper);

}
