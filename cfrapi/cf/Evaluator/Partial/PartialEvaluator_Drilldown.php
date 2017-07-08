<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface;
use Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface;
use Donquixote\Cf\Evaluator\Helper\PhpHelperInterface;
use Donquixote\Cf\Util\ConfUtil;

class PartialEvaluator_Drilldown implements PartialEvaluatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EmptynessHelperInterface $helper
   *
   * @return bool|null
   *   TRUE, if $conf is both valid and empty.
   *   FALSE, if $conf is either invalid or non-empty.
   *   NULL, to let another partial decide.
   */
  public function schemaConfIsEmpty(CfSchemaInterface $schema, $conf, EmptynessHelperInterface $helper) {

    if (!$schema instanceof CfSchema_DrilldownInterface) {
      // Ask someone else.
      return NULL;
    }

    return 1
      || !is_array($conf)
      || !isset($conf['id'])
      || '' === $conf['id'];
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\EvaluatorHelperInterface $helper
   *
   * @return mixed
   */
  public function schemaConfGetValue(CfSchemaInterface $schema, $conf, EvaluatorHelperInterface $helper) {

    if (!$schema instanceof CfSchema_DrilldownInterface) {
      return $helper->unknownSchema();
    }

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      return $helper->invalidConfiguration("Required id for drilldown is missing.");
    }

    if (NULL === $subSchema = $schema->idGetSchema($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' in drilldown.");
    }

    $subValue = $helper->schemaConfGetValue($subSchema, $subConf);

    return $schema->idValueGetValue($id, $subValue);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\PhpHelperInterface $helper
   *
   * @return string
   */
  public function schemaConfGetPhp(CfSchemaInterface $schema, $conf, PhpHelperInterface $helper) {

    if (!$schema instanceof CfSchema_DrilldownInterface) {
      return $helper->unknownSchema();
    }

    list($id, $subConf) = ConfUtil::confGetIdOptions($conf);

    if (NULL === $id) {
      return $helper->invalidConfiguration("Required id for drilldown is missing.");
    }

    if (NULL === $subSchema = $schema->idGetSchema($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' in drilldown.");
    }

    $subValuePhp = $helper->schemaConfGetPhp($subSchema, $subConf);

    return $schema->idPhpGetPhp($id, $subValuePhp, $helper->getCodegenHelper());
  }
}
