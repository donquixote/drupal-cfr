<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Form\D7\Util\D7FormUtil;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Util\ConfUtil;

/**
 * @Cf
 */
class PartialD7Formator_Options implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $schema
   */
  public function __construct(CfSchema_OptionsInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return mixed
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {

    return D7FormUtil::optionsSchemaBuildSelectElement(
      $this->schema,
      ConfUtil::confGetId($conf),
      $label);
  }
}
