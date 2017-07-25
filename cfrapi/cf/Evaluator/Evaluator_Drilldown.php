<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Exception\EvaluatorException_UnsupportedSchema;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\Util\StaUtil;
use Donquixote\Cf\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface;

class Evaluator_Drilldown implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2v;

  /**
   * @var \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
   */
  private $schemaToAnything;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\Evaluator_Drilldown
   */
  public static function createFromDrilldownValSchema(CfSchema_DrilldownValInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return new self($schema->getDecorated(), $schema->getV2V(), $schemaToAnything);
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Evaluator\Evaluator_Drilldown
   */
  public static function createFromDrilldownSchema(CfSchema_DrilldownInterface $schema, SchemaToAnythingInterface $schemaToAnything) {
    return new self($schema, new V2V_Drilldown_Trivial(), $schemaToAnything);
  }

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface $v2v
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   */
  protected function __construct(CfSchema_DrilldownInterface $schema, V2V_DrilldownInterface $v2v, SchemaToAnythingInterface $schemaToAnything) {
    $this->schema = $schema;
    $this->v2v = $v2v;
    $this->schemaToAnything = $schemaToAnything;
  }

  /**
   * @param mixed $conf
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function confGetValue($conf) {

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      throw new EvaluatorException_IncompatibleConfiguration("Required id for drilldown is missing.");
    }

    if (NULL === $subSchema = $this->schema->idGetSchema($id)) {
      throw new EvaluatorException_IncompatibleConfiguration("Unknown id '$id' in drilldown.");
    }

    $subEvaluator = StaUtil::evaluator($subSchema, $this->schemaToAnything);

    if (NULL === $subEvaluator) {
      throw new EvaluatorException_UnsupportedSchema("Unsupported schema for id '$id' in drilldown.");
    }

    $subValue = $subEvaluator->confGetValue($subConf);

    return $this->v2v->idValueGetValue($id, $subValue);
  }

  /**
   * @param mixed $conf
   *
   * @return string
   */
  public function confGetPhp($conf) {

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      $subValuePhp = PhpUtil::incompatibleConfiguration("Required id for drilldown is missing.");
    }
    elseif (NULL === $subSchema = $this->schema->idGetSchema($id)) {
      $subValuePhp = PhpUtil::incompatibleConfiguration("Unknown id '$id' in drilldown.");
    }
    elseif (NULL === $subEvaluator = StaUtil::evaluator($subSchema, $this->schemaToAnything)) {
      $subValuePhp = PhpUtil::unsupportedSchema($subSchema, "Unsupported schema for id '$id' in drilldown.");
    }
    else {
      $subValuePhp = $subEvaluator->confGetPhp($subConf);
    }

    return $this->v2v->idPhpGetPhp($id, $subValuePhp);
  }
}
