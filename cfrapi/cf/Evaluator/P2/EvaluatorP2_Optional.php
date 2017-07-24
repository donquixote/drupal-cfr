<?php

namespace Donquixote\Cf\Evaluator\P2;

use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;

class EvaluatorP2_Optional implements EvaluatorP2Interface {

  /**
   * @var \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface
   */
  private $schema;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface
   */
  public static function create(CfSchema_OptionalInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    $decorated = StaUtil::evaluatorP2($schema->getDecorated(), $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    $emptiness = StaUtil::emptiness($schema->getDecorated(), $schemaToAnything);

    if (NULL === $emptiness) {
      return new self(
        $decorated,
        $schema);
    }

    return new EvaluatorP2_OptionalWithEmptiness(
      $decorated,
      $schema,
      $emptiness);
  }

  /**
   * @param \Donquixote\Cf\Evaluator\P2\EvaluatorP2Interface $decorated
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   */
  public function __construct(EvaluatorP2Interface $decorated, CfSchema_OptionalInterface $schema) {
    $this->decorated = $decorated;
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {

    if (!is_array($conf) || empty($conf['enabled'])) {
      return $this->schema->getEmptyValue();
    }

    $subConf = isset($conf['options'])
      ? $conf['options']
      : NULL;

    return $this->decorated->confGetValue($subConf);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {

    if (!is_array($conf) || empty($conf['enabled'])) {
      return $this->schema->getEmptyPhp();
    }

    $subConf = isset($conf['options'])
      ? $conf['options']
      : NULL;

    return $this->decorated->confGetPhp($subConf);
  }
}
