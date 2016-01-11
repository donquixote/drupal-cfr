<?php

namespace Drupal\cfrreflection\CfrGen\ParamToConfigurator;

use Drupal\cfrapi\Context\CfrContextInterface;

interface ParamToConfiguratorInterface {

  /**
   * @param \ReflectionParameter $param
   * @param \Drupal\cfrapi\Context\CfrContextInterface $context
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface|mixed
   */
  function paramGetConfigurator(\ReflectionParameter $param, CfrContextInterface $context = NULL);

}
