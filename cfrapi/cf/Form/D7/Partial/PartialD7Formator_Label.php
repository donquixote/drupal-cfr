<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\Label\CfSchema_LabelInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

/**
 * @Cf
 */
class PartialD7Formator_Label implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Label\CfSchema_LabelInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Label\CfSchema_LabelInterface $schema
   */
  public function __construct(CfSchema_LabelInterface $schema) {
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
      $this->schema->getLabel());
  }
}
