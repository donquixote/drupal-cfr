<?php

namespace Drupal\cfrapi\RawConfigurator;

use Drupal\cfrapi\ConfToForm\ConfToFormInterface;
use Drupal\cfrapi\ConfToSummary\ConfToSummaryInterface;

/**
 * Parent interface of ConfiguratorInterface, without the confGetValue().
 */
interface RawConfiguratorInterface extends ConfToFormInterface, ConfToSummaryInterface {

}
