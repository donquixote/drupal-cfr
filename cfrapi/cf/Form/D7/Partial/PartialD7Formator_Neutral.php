<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;

/**
 * @Cf
 */
class PartialD7Formator_Neutral implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface $schema
   */
  public function __construct(CfSchema_NeutralInterface $schema) {
    $this->schema = $schema;
  }

  /**
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return array
   */
  public function confGetD7Form($conf, $label, D7FormatorHelperInterface $helper) {

    return $helper->schemaConfGetD7Form(
      $this->schema->getDecorated(), $conf, $label
    );
  }
}
