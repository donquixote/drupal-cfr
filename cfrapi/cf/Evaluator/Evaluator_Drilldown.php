<?php

namespace Donquixote\Cf\Evaluator;

use Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface;
use Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Util\ConfUtil;

class Evaluator_Drilldown implements EvaluatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   */
  public function __construct(CfSchema_DrilldownInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\ConfToValue\Helper\ConfToValueHelperInterface $helper
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

    return $this->schema->idValueGetValue($id, $subValue);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\ConfToPhp\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, PhpHelperInterface $helper) {

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      return $helper->invalidConfiguration("Required id for drilldown is missing.");
    }

    if (NULL === $subSchema = $this->schema->idGetSchema($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' in drilldown.");
    }

    $subValuePhp = $helper->schemaConfGetPhp($subSchema, $subConf);

    return $this->schema->idPhpGetPhp(
      $id,
      $subValuePhp);
  }
}
