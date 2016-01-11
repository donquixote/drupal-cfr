<?php

namespace Drupal\cfrapi\Configurator\Broken;

use Drupal\cfrapi\ConfEmptyness\ConfEmptyness_AlwaysEmpty;
use /** @noinspection PhpDeprecationInspection */
  Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface;
use Drupal\cfrapi\Configurator\Optional\OptionalConfiguratorInterface;

/**
 * @deprecated
 */
class BrokenOptionalConfigurator extends BrokenConfiguratorBase implements OptionalConfiguratorInterface {

  /**
   * @var \Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface
   */
  private $original;

  /**
   * @var mixed|null
   */
  private $defaultValue;

  /**
   * @param \Drupal\cfrapi\Configurator\Optionable\OptionableConfiguratorInterface $original
   * @param mixed|null $defaultValue
   */
  function __construct(
    /** @noinspection PhpDeprecationInspection */
    OptionableConfiguratorInterface $original,
    $defaultValue = NULL
  ) {
    parent::__construct(NULL, array(), NULL);
    $this->original = $original;
    $this->defaultValue = $defaultValue;
  }

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness() {
    // @todo Not sure which emptyness is suitable here.
    return new ConfEmptyness_AlwaysEmpty();
  }
}
