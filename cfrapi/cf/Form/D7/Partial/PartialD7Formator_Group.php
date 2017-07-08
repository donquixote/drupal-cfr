<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;

class PartialD7Formator_Group implements PartialD7FormatorInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   * @param bool $required
   *
   * @return array
   */
  public function schemaConfGetD7Form(
    CfSchemaInterface $schema, $conf, $label, D7FormatorHelperInterface $helper, $required
  ) {
    if (!$schema instanceof CfSchema_GroupInterface) {
      return $helper->unknownSchema();
    }

    if (!$required) {
      return NULL;
    }

    if (!is_array($conf)) {
      $conf = [];
    }

    $labels = $schema->getLabels();

    $form = [];

    if (NULL !== $label && '' !== $label) {
      $form['#title'] = $label;
    }

    foreach ($schema->getItemSchemas() as $key => $itemSchema) {

      $itemConf = isset($conf[$key])
        ? $conf[$key]
        : NULL;

      $itemLabel = isset($labels[$key])
        ? $labels[$key]
        : $key;

      $form[$key] = $helper->schemaConfGetD7Form(
        $itemSchema,
        $itemConf,
        $itemLabel);
    }

    return $form;
  }
}
