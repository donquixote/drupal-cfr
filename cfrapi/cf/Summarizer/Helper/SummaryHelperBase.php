<?php

namespace Donquixote\Cf\Summarizer\Helper;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Summarizer\Partial\PartialSummarizerInterface;
use Donquixote\Cf\Translator\TranslatorDecoratorBase;
use Donquixote\Cf\Util\HtmlUtil;

abstract class SummaryHelperBase extends TranslatorDecoratorBase implements SummaryHelperInterface {

  /**
   * @return mixed
   */
  protected function unsupportedSchema() {
    return $this->translate('Unsupported schema.');
  }

  /**
   * @param mixed $conf
   * @param string $message
   *
   * @return mixed
   */
  public function incompatibleConfiguration($conf, $message) {
    return $this->translate('Incompatible configuration')
      . ': '
      . HtmlUtil::sanitize($message);
  }

  /**
   * @param string $message
   *
   * @return mixed
   */
  public function invalidConfiguration($message) {
    return $this->translate('Invalid configuration')
      . ': '
      . HtmlUtil::sanitize($message);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   *
   * @return string
   */
  public function schemaConfGetSummary(CfSchemaInterface $schema, $conf) {

    $partial = $this->schemaGetPartial($schema);

    if (NULL === $partial) {
      return $this->unsupportedSchema();
    }

    if (!$partial instanceof PartialSummarizerInterface) {
      return $this->unsupportedSchema();
    }

    return $partial->schemaConfGetSummary($conf, $this);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return mixed
   */
  abstract protected function schemaGetPartial(CfSchemaInterface $schema);
}
