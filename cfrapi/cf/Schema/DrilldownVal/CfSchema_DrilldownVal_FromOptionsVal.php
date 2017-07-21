<?php

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_OptionsSchemaNull;
use Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface;
use Donquixote\Cf\V2V\Drilldown\V2V_Drilldown_FromIdV2V;

abstract class CfSchema_DrilldownVal_FromOptionsVal extends CfSchema_DrilldownValBase {

  /**
   * @param \Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface $optionsValSchema
   *
   * @return \Donquixote\Cf\Schema\DrilldownVal\CfSchema_DrilldownVal
   */
  public static function create(CfSchema_OptionsValInterface $optionsValSchema) {

    return new CfSchema_DrilldownVal(
      new CfSchema_Drilldown_OptionsSchemaNull(
        $optionsValSchema->getDecorated()),
      new V2V_Drilldown_FromIdV2V(
        $optionsValSchema->getV2V()));
  }
}
