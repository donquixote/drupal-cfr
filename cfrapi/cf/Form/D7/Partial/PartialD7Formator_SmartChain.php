<?php

namespace Donquixote\Cf\Form\D7\Partial;

use Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;

/**
 * A "chain-of-responsibility" that remembers whether a partial does or does not
 * support a schema class.
 */
class PartialD7Formator_SmartChain implements PartialD7FormatorInterface {

  /**
   * @var \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface[]
   *   Format: $[] = $mapper
   */
  private $mappers;

  /**
   * @var \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface[]
   *   Format: $[$class] = $mapper
   */
  private $summaryMappersByClass = [];

  /**
   * @var \Donquixote\Cf\Form\D7\Partial\PartialD7Formator_NoKnownSchema
   */
  private $noKnownSchema;

  /**
   * @param \Donquixote\Cf\Form\D7\Partial\PartialD7FormatorInterface[] $mappers
   */
  public function __construct(array $mappers) {
    $this->mappers = $mappers;
    $this->noKnownSchema = new PartialD7Formator_NoKnownSchema();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $label
   * @param \Donquixote\Cf\Form\D7\Helper\D7FormatorHelperInterface $helper
   * @param bool $required
   *
   * @return array|null
   */
  public function schemaConfGetD7Form(CfSchemaInterface $schema, $conf, $label, D7FormatorHelperInterface $helper, $required) {

    $class = get_class($schema);

    if (isset($this->summaryMappersByClass[$class])) {
      return $this->summaryMappersByClass[$class]->schemaConfGetD7Form($schema, $conf, $label, $helper, $required);
    }

    $unknownSchema = $helper->unknownSchema();

    foreach ($this->mappers as $mapper) {
      if ($unknownSchema !== $form = $mapper->schemaConfGetD7Form($schema, $conf, $label, $helper, $required)) {
        $this->summaryMappersByClass[$class] = $mapper;
        return $form;
      }
    }

    $this->summaryMappersByClass[$class] = $this->noKnownSchema;
    return $unknownSchema;
  }
}
