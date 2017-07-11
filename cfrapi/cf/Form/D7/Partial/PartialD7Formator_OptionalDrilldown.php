<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;

class PartialD7Formator_OptionalDrilldown implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   */
  public function __construct(CfSchema_DrilldownInterface $schema) {
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

    $form = $helper->schemaConfGetD7Form($this->schema, $conf, $label);

    // @todo Some sanity checks?
    unset($form['id']['#required']);
    $form['id']['#empty_value'] = '';

    return $form;
  }
}
