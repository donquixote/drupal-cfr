<?php

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\Exception\EvaluatorException_IncompatibleConfiguration;
use Donquixote\Cf\Exception\EvaluatorException_UnsupportedSchema;
use Donquixote\Cf\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_InlineExpandedUnfaithful;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\Schema\Neutral\CfSchema_NeutralInterface;
use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;
use Donquixote\Cf\Schema\OptionsVal\CfSchema_OptionsValInterface;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface;

/**
 * @todo Split a part of this into a V2V class.
 */
class CfSchema_DrilldownVal_InlineExpanded extends CfSchema_DrilldownValBase implements V2V_DrilldownInterface {

  /**
   * @var \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  private $drilldown;

  /**
   * @var \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface
   */
  private $v2v;

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $idIsInline
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createOrSame(
    CfSchemaInterface $schema,
    CfSchema_IdInterface $idIsInline
  ) {

    if (NULL === $candidate = self::createOrNull($schema, $idIsInline)) {
      return $schema;
    }

    return $candidate;
  }

  /**
   *
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $idIsInline
   *
   * @return self
   */
  public static function createOrNull(
    CfSchemaInterface $schema,
    CfSchema_IdInterface $idIsInline
  ) {

    if ($schema instanceof CfSchema_DrilldownInterface) {
      return self::createFromDrilldownSchema($schema, $idIsInline);
    }

    if ($schema instanceof CfSchema_DrilldownValInterface) {
      return self::createFromDrilldownValSchema($schema, $idIsInline);
    }

    if ($schema instanceof CfSchema_NeutralInterface) {
      return self::createOrNull($schema->getDecorated(), $idIsInline);
    }

    return NULL;
  }

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $schema
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $idIsInline
   *
   * @return self
   */
  public static function createFromDrilldownSchema(
    CfSchema_DrilldownInterface $schema,
    CfSchema_IdInterface $idIsInline
  ) {

    return new self(
      $schema,
      $idIsInline,
      new V2V_Drilldown_Trivial());
  }

  public static function createFromDrilldownValSchema(
    CfSchema_DrilldownValInterface $schema,
    CfSchema_IdInterface $idIsInline
  ) {

    return new self(
      $schema->getDecorated(),
      $idIsInline,
      $schema->getV2V());
  }

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $drilldown
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $idIsInline
   * @param \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface $v2v
   */
  public function __construct(
    CfSchema_DrilldownInterface $drilldown,
    CfSchema_IdInterface $idIsInline,
    V2V_DrilldownInterface $v2v
  ) {

    parent::__construct(new CfSchema_Drilldown_InlineExpandedUnfaithful(
      $drilldown,
      $idIsInline));

    $this->drilldown = $drilldown;
    $this->v2v = $v2v;
  }

  /**
   * @param string|int $combinedId
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idValueGetValue($combinedId, $value) {

    if (FALSE === strpos($combinedId, '/')) {
      return $this->v2v->idValueGetValue($combinedId, $value);
    }

    list($prefix, $suffix) = explode('/', $combinedId, 2);

    if (NULL === $schema = $this->drilldown->idGetSchema($prefix)) {
      throw new EvaluatorException_IncompatibleConfiguration("Unknown inline id $prefix + $suffix.");
    }

    $value = $this->schemaProcessValue($schema, $suffix, $value);

    $value = $this->v2v->idValueGetValue($prefix, $value);

    return $value;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  private function schemaProcessValue(CfSchemaInterface $schema, $id, $value) {

    if ($schema instanceof CfSchema_DrilldownInterface) {
      return $value;
    }

    if ($schema instanceof CfSchema_OptionsInterface) {
      return $id;
    }

    if ($schema instanceof CfSchema_DrilldownValInterface) {
      return $schema->getV2V()->idValueGetValue($id, $value);
    }

    if ($schema instanceof CfSchema_OptionsValInterface) {
      return $schema->idGetValue($id);
    }

    if ($schema instanceof CfSchema_NeutralInterface) {
      return $this->schemaProcessValue($schema->getDecorated(), $id, $value);
    }

    throw new EvaluatorException_UnsupportedSchema("Failed to evaluate inline drilldown.");
  }

  /**
   * @param string|int $combinedId
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($combinedId, $php) {

    if (FALSE === /* $pos = */ strpos($combinedId, '/')) {
      return $this->v2v->idPhpGetPhp($combinedId, $php);
    }

    list($prefix, $suffix) = explode('/', $combinedId, 2);

    if (NULL === $schema = $this->drilldown->idGetSchema($prefix)) {
      return PhpUtil::exception(
        EvaluatorException_IncompatibleConfiguration::class,
        "Unknown inline id $prefix + $suffix.");
    }

    $php = $this->schemaProcessPhp($schema, $suffix, $php);

    $php = $this->v2v->idPhpGetPhp($prefix, $php);

    return $php;
  }

  /**
   * @param \Donquixote\Cf\Schema\CfSchemaInterface $schema
   * @param string|int $id
   * @param string $php
   *
   * @return string
   */
  private function schemaProcessPhp(CfSchemaInterface $schema, $id, $php) {

    if ($schema instanceof CfSchema_DrilldownInterface) {
      return $php;
    }

    if ($schema instanceof CfSchema_OptionsInterface) {
      return var_export($id, TRUE);
    }

    if ($schema instanceof CfSchema_DrilldownValInterface) {
      return $schema->getV2V()->idPhpGetPhp($id, $php);
    }

    if ($schema instanceof CfSchema_OptionsValInterface) {
      return $schema->idGetPhp($id);
    }

    if ($schema instanceof CfSchema_NeutralInterface) {
      return $this->schemaProcessPhp($schema->getDecorated(), $id, $php);
    }

    return PhpUtil::unsupportedSchema($schema, "Failed to evaluate inline drilldown.");
  }

  /**
   * @return \Donquixote\Cf\V2V\Drilldown\V2V_DrilldownInterface
   */
  public function getV2V() {
    return $this;
  }
}
