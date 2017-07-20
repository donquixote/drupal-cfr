<?php

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_OptionsSchemaNull;
use Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface;

class CfSchema_DrilldownVal_FromOptionsVal extends CfSchema_DrilldownValBase {

  /**
   * @var \Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface
   */
  private $optionsValSchema;

  /**
   * @param \Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface $optionsValSchema
   */
  public function __construct(CfSchema_OptionsValInterface $optionsValSchema) {
    parent::__construct(
      new CfSchema_Drilldown_OptionsSchemaNull(
        $optionsValSchema->getDecorated()));
    $this->optionsValSchema = $optionsValSchema;
  }

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idValueGetValue($id, $value) {
    return $this->optionsValSchema->idGetValue($id);
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, $php) {
    return $this->optionsValSchema->idGetPhp($id);
  }
}
