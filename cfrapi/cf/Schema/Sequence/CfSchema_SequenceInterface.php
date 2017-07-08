<?php

namespace Donquixote\Cf\Schema\Sequence;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\Transformable\CfSchema_TransformableInterface;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;

interface CfSchema_SequenceInterface extends CfSchema_TransformableInterface {

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getItemSchema();

  /**
   * @param mixed[] $values
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values);

  /**
   * @param string[] $itemsPhp
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp, CfrCodegenHelperInterface $helper);

  /**
   * @param int $delta
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return string
   */
  public function deltaGetItemLabel($delta, D7FormatorHelperInterface $helper);

}
