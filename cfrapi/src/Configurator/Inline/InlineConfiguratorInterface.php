<?php

namespace Drupal\cfrapi\Configurator\Inline;

use Drupal\cfrapi\Configurator\ConfiguratorInterface;

interface InlineConfiguratorInterface extends ConfiguratorInterface {

  /**
   * @return string[]
   */
  function getInlineOptions();

}
