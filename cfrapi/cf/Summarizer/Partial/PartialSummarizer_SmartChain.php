<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;

/**
 * A "chain-of-responsibility" that remembers whether a partial does or does not
 * support a schema class.
 */
class PartialSummarizer_SmartChain implements PartialSummarizerInterface {

  /**
   * @var \Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface[]
   *   Format: $[] = $mapper
   */
  private $mappers;

  /**
   * @var \Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface[]
   *   Format: $[$class] = $mapper
   */
  private $summaryMappersByClass = [];

  /**
   * @var \Donquixote\Cf\Summarizer\Partial\PartialSummarizer_NoKnownSchema
   */
  private $noKnownSchema;

  /**
   * @param \Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface[] $mappers
   */
  public function __construct(array $mappers) {
    $this->mappers = $mappers;
    $this->noKnownSchema = new PartialSummarizer_NoKnownSchema();
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return mixed
   * @throws \Drupal\cfrapi\Exception\InvalidConfigurationException
   */
  public function schemaConfGetSummary(CfSchemaInterface $schema, $conf, SummaryHelperInterface $helper) {

    $class = get_class($schema);

    if (isset($this->summaryMappersByClass[$class])) {
      return $this->summaryMappersByClass[$class]->schemaConfGetSummary($schema, $conf, $helper);
    }

    $unknownSchema = $helper->unknownSchema();

    foreach ($this->mappers as $mapper) {
      if ($unknownSchema !== $value = $mapper->schemaConfGetSummary($schema, $conf, $helper)) {
        $this->summaryMappersByClass[$class] = $mapper;
        return $value;
      }
    }

    $this->summaryMappersByClass[$class] = $this->noKnownSchema;
    return $unknownSchema;
  }
}
