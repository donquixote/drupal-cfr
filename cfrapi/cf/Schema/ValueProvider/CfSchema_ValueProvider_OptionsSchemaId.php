<?php

namespace Donquixote\Cf\Schema\ValueProvider;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

class CfSchema_ValueProvider_OptionsSchemaId implements CfSchema_ValueProviderInterface {

  /**
   * @var \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface
   */
  private $optionsSchema;

  /**
   * @var int|string
   */
  private $id;

  /**
   * @param \Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface $optionsSchema
   * @param string|int $id
   */
  public function __construct(CfSchema_OptionsInterface $optionsSchema, $id) {
    $this->optionsSchema = $optionsSchema;
    $this->id = $id;
  }

  /**
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function getValue() {
    return $this->optionsSchema->idGetValue($this->id);
  }

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp() {
    return $this->optionsSchema->idGetPhp($this->id);
  }
}
