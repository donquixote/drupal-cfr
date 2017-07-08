<?php

namespace Donquixote\Cf\Schema\Sequence;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface;

class CfSchema_Sequence extends CfSchema_Sequence_PassthruBase {

  /**
   * @var \Donquixote\Cf\Schema\CfSchemaInterface
   */
  private $itemSchema;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $itemSchema
   */
  public function __construct(CfSchemaInterface $itemSchema) {
    $this->itemSchema = $itemSchema;
  }

  /**
   * Returns a version of this schema where internal schemas are replaced,
   * recursively.
   *
   * @param \Drupal\cfrapi\CfrSchemaReplacer\CfrSchemaReplacerInterface $replacer
   *
   * @return static
   */
  public function withReplacements(CfrSchemaReplacerInterface $replacer) {

    if (NULL === $replacement = $replacer->schemaGetReplacement($this->itemSchema)) {
      return $this;
    }

    $clone = clone $this;
    $clone->itemSchema = $replacement;
    return $clone;
  }

  /**
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public function getItemSchema() {
    return $this->itemSchema;
  }

  /**
   * @param int $delta
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   *
   * @return string
   */
  public function deltaGetItemLabel($delta, D7FormatorHelperInterface $helper) {

    return (NULL === $delta)
      ? $helper->translate('New item')
      : $helper->translate(
        'Item !n',
        ['!n' => '#' . (int)$delta]);
  }
}
