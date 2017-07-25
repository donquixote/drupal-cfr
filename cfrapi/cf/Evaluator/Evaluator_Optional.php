<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\StaUtil;

class Evaluator_Optional implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Evaluator\EvaluatorInterface
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
   * @return \Donquixote\Cf\Evaluator\EvaluatorInterface
   */
  public static function create(CfSchema_OptionalInterface $schema, SchemaToAnythingInterface $schemaToAnything) {

    $decorated = StaUtil::evaluator($schema->getDecorated(), $schemaToAnything);

    if (NULL === $decorated) {
      return NULL;
    }

    $emptiness = StaUtil::emptiness($schema->getDecorated(), $schemaToAnything);

    if (NULL === $emptiness) {
      return new self(
        $decorated,
        $schema);
    }

    return new Evaluator_OptionalWithEmptiness(
      $decorated,
      $schema,
      $emptiness);
  }

  /**
   * @param \Donquixote\Cf\Evaluator\EvaluatorInterface $decorated
   * @param \Donquixote\Cf\Schema\Optional\CfSchema_OptionalInterface $schema
   */
  public function __construct(EvaluatorInterface $decorated, CfSchema_OptionalInterface $schema) {
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
