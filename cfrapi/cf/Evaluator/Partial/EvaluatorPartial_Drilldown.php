<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface;

class EvaluatorPartial_Drilldown implements EvaluatorPartialInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2v;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownValInterface $schema
   *
   * @return self
   */
  public static function createFromDrilldownValSchema(CfSchema_DrilldownValInterface $schema) {
    return new self($schema->getDecorated(), $schema);
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   *
   * @return self
   */
  public static function createFromDrilldownSchema(CfSchema_DrilldownInterface $schema) {
    return new self($schema, new V2V_Drilldown_Trivial());
  }

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface $v2v
   */
  protected function __construct(CfSchema_DrilldownInterface $schema, V2V_DrilldownInterface $v2v) {
    $this->schema = $schema;
    $this->v2v = $v2v;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      return $helper->invalidConfiguration("Required id for drilldown is missing.");
    }

    if (NULL === $subSchema = $this->schema->idGetSchema($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' in drilldown.");
    }

    $subValue = $helper->schemaConfGetValue($subSchema, $subConf);

    return $this->v2v->idValueGetValue($id, $subValue);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      $subValuePhp = $helper->invalidConfiguration("Required id for drilldown is missing.");
    }
    elseif (NULL === $subSchema = $this->schema->idGetSchema($id)) {
      $subValuePhp = $helper->invalidConfiguration("Unknown id '$id' in drilldown.");
    }
    else {
      $subValuePhp = $helper->schemaConfGetPhp($subSchema, $subConf);
    }


    return $this->v2v->idPhpGetPhp($id, $subValuePhp);
  }
}
