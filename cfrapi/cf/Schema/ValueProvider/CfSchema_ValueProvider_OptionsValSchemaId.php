<?php

namespace Donquixote\Cf\Schema\ValueProvider;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface;
use Donquixote\Cf\Util\PhpUtil;

class CfSchema_ValueProvider_OptionsValSchemaId implements CfSchema_ValueProviderInterface {

  /**
   * @var \Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface
   */
  private $optionsValSchema;

  /**
   * @var int|string
   */
  private $id;

  /**
   * @param \Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface $optionsValSchema
   * @param string|int $id
   */
  public function __construct(CfSchema_OptionsValInterface $optionsValSchema, $id) {
    $this->optionsValSchema = $optionsValSchema;
    $this->id = $id;
  }

  /**
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function getValue() {

    if (!$this->optionsValSchema->getDecorated()->idIsKnown($this->id)) {
      throw new EvaluatorException_IncompatibleConfiguration("Unknown id $this->id for options schema.");
    }

    return $this->optionsValSchema->getV2V()->idGetValue($this->id);
  }

  /**
   * @return string
   *   PHP statement to generate the value.
   */
  public function getPhp() {

    if (!$this->optionsValSchema->getDecorated()->idIsKnown($this->id)) {
      return PhpUtil::exception(
        EvaluatorException_IncompatibleConfiguration::class,
        "Unknown id $this->id for options schema.");
    }

    return $this->optionsValSchema->getV2V()->idGetPhp($this->id);
  }
}
