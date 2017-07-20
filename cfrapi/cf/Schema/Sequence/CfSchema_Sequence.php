<?php

namespace Donquixote\Cf\Schema\Sequence;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class CfSchema_Sequence implements CfSchema_SequenceInterface {

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
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return static
   */
  public function withReplacements(SchemaReplacerInterface $replacer) {

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
