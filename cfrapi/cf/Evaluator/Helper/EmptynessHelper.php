<?php

namespace Donquixote\Cf\Evaluator\Helper;

use Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface;
use Donquixote\Cf\Helper\SchemaHelperBase;
use Donquixote\Cf\Schema\CfSchemaInterface;

class EmptynessHelper extends SchemaHelperBase implements EmptynessHelperInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface
   */
  private $partialEvaluator;

  /**
   * @param \Donquixote\Cf\Evaluator\Partial\PartialEvaluatorInterface $partial
   */
  public function __construct(PartialEvaluatorInterface $partial) {
    $this->partialEvaluator = $partial;
  }

  /**
   * @return mixed|bool
   */
  public function noNaturalEmptyness() {
    return 'GENERIC';
  }

  /**
   * @return mixed
   */
  public function unknownSchema() {
    // Return something that is not TRUE or FALSE.
    return NULL;
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return mixed
   */
  public function incompatibleConfiguration($conf, $message) {
    return FALSE;
  }

  /**
   * @param string $message
   *
   * @return mixed
   */
  public function invalidConfiguration($message) {
    return FALSE;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return bool|null
   */
  public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf) {

    return $this->partialEvaluator->schemaConfIsEmpty($schema, $conf, $this);
  }
}
