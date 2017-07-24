<?php

namespace Donquixote\Cf\Emptiness;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Util\UtilBase;

final class EmptinessSTAFactories extends UtilBase {

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $schema
   *
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  public static function fromOptionsSchema(
    /** @noinspection PhpUnusedParameterInspection */
    CfSchema_OptionsInterface $schema
  ) {
    return new Emptiness_Enum();
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   *
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  public static function fromDrilldownSchema(
    /** @noinspection PhpUnusedParameterInspection */
    CfSchema_DrilldownInterface $schema
  ) {
    return new Emptiness_Key('id');
  }

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface $schema
   *
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface
   */
  public static function fromOptionless(
    /** @noinspection PhpUnusedParameterInspection */
    CfSchema_OptionlessInterface $schema
  ) {
    return new Emptiness_Bool();
  }

}
