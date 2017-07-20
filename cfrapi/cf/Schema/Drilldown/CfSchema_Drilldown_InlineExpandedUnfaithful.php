<?php

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

class CfSchema_Drilldown_InlineExpandedUnfaithful implements CfSchema_DrilldownInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  private $idIsInline;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $idIsInline
   */
  public function __construct(CfSchema_DrilldownInterface $decorated, CfSchema_IdInterface $idIsInline) {
    $this->decorated = $decorated;
    $this->idIsInline = $idIsInline;
  }

  /**
   * @param string|mixed $id
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
   * @param string|mixed $id
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
      return new CfSchema_Drilldown_OptionsSchemaNull($schema);
    }

    if ($schema instanceof CfSchema_ValueToValueBaseInterface) {
      return $this->schemaGetAsDrilldown($schema->getDecorated());
    }

    return NULL;
  }

  /**
   * @param string|int $id
   *
   * @return bool
   */
  protected function idIsInlined($id) {
    return $this->idIsInline->idIsKnown($id);
  }
}
