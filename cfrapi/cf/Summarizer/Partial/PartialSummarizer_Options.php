<?php

namespace Donquixote\Cf\Summarizer\Partial;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface;
use Donquixote\Cf\Util\ConfUtil;

class PartialSummarizer_Options implements PartialSummarizerInterface {

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $optionsSchema
   * @param mixed $conf
   * @param \Donquixote\Cf\Summarizer\Helper\SummaryHelperInterface $helper
   *
   * @return mixed
   */
  public function schemaConfGetSummary(CfSchemaInterface $optionsSchema, $conf, SummaryHelperInterface $helper) {

    if (!$optionsSchema instanceof CfSchema_OptionsInterface) {
      return $helper->unknownSchema();
    }

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return $helper->invalidConfiguration('Required id empty for options schema.');
    }

    if (!$optionsSchema->idIsKnown($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' for options schema.");
    }

    return $optionsSchema->idGetValue($id);
  }
}
