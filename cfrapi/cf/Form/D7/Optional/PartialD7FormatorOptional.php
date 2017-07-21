<?php

namespace Donquixote\Cf\Form\D7\Optional;

use Donquixote\Cf\Emptyness\EmptynessInterface;
use Donquixote\Cf\Form\D7\Partial\PartialD7Formator_Bool;
use Donquixote\Cf\Form\D7\Partial\PartialD7Formator_OptionalDrilldown;
use Donquixote\Cf\Form\D7\Partial\PartialD7Formator_OptionalOptions;
use Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Optionless\CfSchema_OptionlessInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

class PartialD7FormatorOptional implements PartialD7FormatorOptionalInterface {

  /**
   * @var \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface
   */
  private $partialD7Formator;

  /**
   * @var \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  private $emptyness;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decoratedSchema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Form\D7\Optional\PartialD7FormatorOptionalInterface|null
   */
  public static function create(
    CfSchemaInterface $decoratedSchema,
    SchemaToAnythingInterface $schemaToAnything
  ) {

    $emptyness = $schemaToAnything->schema(
      $decoratedSchema,
      EmptynessInterface::class);

    if (NULL === $emptyness || !$emptyness instanceof EmptynessInterface) {
      return NULL;
    }

    if (NULL === $formator = self::createFormator($decoratedSchema)) {
      // @todo This is a special case.
      return NULL;
    }

    return new self($formator, $emptyness);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $decoratedSchema
   *
   * @return \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface|null
   */
  public static function createFormator(CfSchemaInterface $decoratedSchema) {

    if ($decoratedSchema instanceof CfSchema_DrilldownInterface) {
      return new PartialD7Formator_OptionalDrilldown($decoratedSchema);
    }

    if ($decoratedSchema instanceof CfSchema_OptionsInterface) {
      return new PartialD7Formator_OptionalOptions($decoratedSchema);
    }

    if ($decoratedSchema instanceof CfSchema_ValueToValueBaseInterface) {
      return self::createFormator($decoratedSchema->getDecorated());
    }

    if ($decoratedSchema instanceof CfSchema_OptionlessInterface) {
      return new PartialD7Formator_Bool();
    }

    // @todo Do something! An emptyness-rewrite thingie.
    # kdpm(get_defined_vars(), __METHOD__);
    return NULL;
  }

  /**
   * @param \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface $partialD7Formator
   * @param \Donquixote\Cf\Emptyness\EmptynessInterface $emptyness
   */
  public function __construct(
    PartialD7FormatorInterface $partialD7Formator,
    EmptynessInterface $emptyness
  ) {
    $this->partialD7Formator = $partialD7Formator;
    $this->emptyness = $emptyness;
  }

  /**
   * @return \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface
   */
  public function getFormator() {
    return $this->partialD7Formator;
  }

  /**
   * @return \Donquixote\Cf\Emptyness\EmptynessInterface
   */
  public function getEmptyness() {
    return $this->emptyness;
  }
}
