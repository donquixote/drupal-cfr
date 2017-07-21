<?php

namespace Donquixote\Cf\Evaluator\P2;

use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\V2V\Value\V2V_ValueInterface;

class EvaluatorP2_ValueToValue extends EvaluatorP2_DecoratorBase {

  /**
   * @var \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface
   */
  private $v2v;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface $valueToValueSchema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return self|null
   */
  public static function create(CfSchema_ValueToValueInterface $valueToValueSchema, SchemaToAnythingInterface $schemaToAnything) {

    $decorated = $schemaToAnything->schema(
      $valueToValueSchema->getDecorated(),
      EvaluatorP2Interface::class);

    if (NULL === $decorated || !$decorated instanceof EvaluatorP2Interface) {
      return NULL;
    }

    return new self($decorated, $valueToValueSchema->getV2V());
  }

  /**
   * @param \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface $decorated
   * @param \Donquixote\Cf\V2V\Value\V2V_ValueInterface $v2v
   */
  public function __construct(EvaluatorP2Interface $decorated, V2V_ValueInterface $v2v) {
    parent::__construct($decorated);
    $this->v2v = $v2v;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {
    $value = parent::confGetValue($conf);
    return $this->v2v->valueGetValue($value);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {
    $php = parent::confGetPhp($conf);
    return $this->v2v->phpGetPhp($php);
  }
}
