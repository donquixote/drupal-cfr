<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;

class PartialD7Formator_Group implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface
   */
  private $schema;

  /**
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $schema
   */
  public function __construct(CfSchema_GroupInterface $schema) {
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

    if (!is_array($conf)) {
      $conf = [];
    }

    $labels = $this->schema->getLabels();

    $form = [];

    if (NULL !== $label && '' !== $label) {
      $form['#title'] = $label;
    }

    foreach ($this->schema->getItemSchemas() as $key => $itemSchema) {

      $itemConf = isset($conf[$key])
        ? $conf[$key]
        : NULL;

      $itemLabel = isset($labels[$key])
        ? $labels[$key]
        : $key;

      $form[$key] = $helper->schemaConfGetD7Form(
        $itemSchema, $itemConf, $itemLabel
      );
    }

    return $form;
  }
}
