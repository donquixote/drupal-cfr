<?php

namespace Drupal\cfrfamily\DrilldownSchema;

use Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_FromOptionsSchema;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Schema\ValueToValue\CfSchema_ValueToValueInterface;

abstract class CfSchema_Drilldown_InlineExpandedBase implements CfSchema_DrilldownInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   */
  public function __construct(CfSchema_DrilldownInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @return string[][]
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function getGroupedOptions() {

    $options = [];
    foreach ($this->decorated->getGroupedOptions() as $groupLabel => $groupOptions) {
      foreach ($groupOptions as $id => $label) {

        if (NULL === $inlineOptions = $this->idGetInlineOptions($id)) {
          $options[$groupLabel][$id] = $label;
        }
        else {
          foreach ($inlineOptions as $inlineGroupLabel => $inlineGroupOptions) {
            foreach ($inlineGroupOptions as $inlineId => $inlineLabel) {
              $options[$inlineGroupLabel]["$id/$inlineId"] = "$label: $inlineLabel";
            }
          }
          $options[$groupLabel][$id] = "$label - ALL";
        }
      }
    }

    return $options;
  }

  /**
   * @param string $id
   *
   * @return null|string[][]
   */
  private function idGetInlineOptions($id) {

    if (!$this->idIsInlined($id)) {
      return NULL;
    }

    if (NULL === $schema = $this->idGetDrilldownSchema($id)) {
      return NULL;
    }

    return $schema->getGroupedOptions();
  }

  /**
   * @param string|int $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {

    if (NULL === $schema = $this->combinedIdGetNestedSchema($id)) {
      return NULL;
    }

    return $schema->idGetLabel($id);
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  public function idIsKnown($id) {

    if (NULL === $schema = $this->combinedIdGetNestedSchema($id)) {
      return FALSE;
    }

    return $schema->idIsKnown($id);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface|null
   */
  public function idGetSchema($id) {

    if (NULL === $schema = $this->combinedIdGetNestedSchema($id)) {
      return NULL;
    }

    return $schema->idGetSchema($id);
  }

  /**
   * @param string|int $combinedId
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Drupal\cfrapi\Exception\ConfToValueException
   */
  public function idValueGetValue($combinedId, $value) {

    if (FALSE === strpos($combinedId, '/')) {
      return $this->decorated->idValueGetValue($combinedId, $value);
    }

    list($prefix, $suffix) = explode('/', $combinedId, 2);

    if (NULL === $schema = $this->idGetDrilldownSchema($prefix)) {
      return NULL;
    }

    $value = $schema->idValueGetValue($suffix, $value);

    $value = $this->decorated->idValueGetValue($prefix, $value);

    return $value;
  }

  /**
   * @param string|int $combinedId
   * @param string $php
   * @param \Drupal\cfrapi\CfrCodegenHelper\CfrCodegenHelperInterface $helper
   *
   * @return mixed
   */
  public function idPhpGetPhp($combinedId, $php, CfrCodegenHelperInterface $helper) {

    if (FALSE === /* $pos = */ strpos($combinedId, '/')) {
      return $this->decorated->idPhpGetPhp($combinedId, $php, $helper);
    }

    list($prefix, $suffix) = explode('/', $combinedId, 2);

    if (NULL === $schema = $this->idGetDrilldownSchema($prefix)) {
      return NULL;
    }

    $php = $schema->idPhpGetPhp($suffix, $php, $helper);

    $php = $this->decorated->idPhpGetPhp($prefix, $php, $helper);

    return $php;
  }

  /**
   * @param string|int $combinedId
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface|null
   */
  private function combinedIdGetNestedSchema(&$combinedId) {

    if (FALSE === /* $pos = */ strpos($combinedId, '/')) {
      return $this->decorated;
    }

    list($prefix, $combinedId) = explode('/', $combinedId, 2);

    return $this->idGetDrilldownSchema($prefix);
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private function idGetDrilldownSchema($id) {

    if (NULL === $schema = $this->decorated->idGetSchema($id)) {
      return NULL;
    }

    return $this->schemaGetAsDrilldown($schema);
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private function schemaGetAsDrilldown(CfSchemaInterface $schema) {

    if ($schema instanceof CfSchema_DrilldownInterface) {
      return $schema;
    }

    if ($schema instanceof CfSchema_OptionsInterface) {
      return new CfSchema_Drilldown_FromOptionsSchema($schema);
    }

    if ($schema instanceof CfSchema_ValueToValueInterface) {
      if (NULL === $drilldown = $this->schemaGetAsDrilldown(
        $schema->getDecorated())
      ) {
        return NULL;
      }

      return new CfSchema_Drilldown_ValueToValue($drilldown, $schema);
    }

    return NULL;
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  abstract protected function idIsInlined($id);
}
