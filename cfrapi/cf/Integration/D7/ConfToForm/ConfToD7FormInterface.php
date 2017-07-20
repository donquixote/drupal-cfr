<?php

namespace Donquixote\Cf\Integration\D7\ConfToForm;

use Donquixote\Cf\Schema\CfSchemaInterface;

/**
 * Legacy integration interface layer to turn all configurators into schemas.
 */
interface ConfToD7FormInterface extends CfSchemaInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  public function confGetForm($conf, $label);

}
