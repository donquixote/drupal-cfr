<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

/**
 * @Cf
 */
class PartialD7Formator_V2V implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface $schema
   */
  public function __construct(CfSchema_ValueToValueBaseInterface $schema) {
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
      $this->schema->getDecorated(),
      $conf,
      $label);
  }
}
