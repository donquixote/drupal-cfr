<?php

namespace Drupal\cfrapi\ConfToForm;

/**
 * @deprecated
 */
interface OptionableConfToFormInterface extends ConfToFormInterface {

  /**
   * @param mixed $conf
   *   Configuration from a form, config file or storage.
   * @param string|null $label
   *   Label for the form element, specifying the purpose where it is used.
   *
   * @return array
   */
  function confGetOptionalForm($conf, $label);

  /**
   * @return \Drupal\cfrapi\ConfEmptyness\ConfEmptynessInterface
   */
  function getEmptyness();

}
