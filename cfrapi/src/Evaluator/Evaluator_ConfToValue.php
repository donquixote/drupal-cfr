<?php

namespace Drupal\cfrapi\Evaluator;

use Donquixote\Cf\Evaluator\EvaluatorInterface;
use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartial_CallbackNoHelper;
use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelper;
use Drupal\cfrapi\ConfToValue\ConfToValueInterface;

class Evaluator_ConfToValue implements EvaluatorInterface {

  /**
   * @var \Drupal\cfrapi\ConfToValue\ConfToValueInterface
   */
  private $schema;

  /**
   * @Cf
   *
   * @return \Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface
   */
  public static function sta() {

    return SchemaToAnythingPartial_CallbackNoHelper::fromClassName(
      __CLASS__,
      ConfToValueInterface::class);
  }

  /**
   * @param \Drupal\cfrapi\ConfToValue\ConfToValueInterface $schema
   */
  public function __construct(ConfToValueInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   */
  public function confGetValue($conf) {
    return $this->schema->confGetValue($conf);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {
    return $this->schema->confGetPhp($conf, new CfrCodegenHelper());
  }
}
