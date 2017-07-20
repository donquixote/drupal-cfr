<?php

namespace Donquixote\Cf\Evaluator\Partial;

use Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface;
use Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface;
use Donquixote\Cf\Schema\Id\CfSchema_IdInterface;
use Donquixote\Cf\Schema\IdVal\CfSchema_IdValInterface;
use Donquixote\Cf\Util\ConfUtil;
use Donquixote\Cf\Util\PhpUtil;
use Donquixote\Cf\V2V\Id\V2V_Id_Trivial;
use Donquixote\Cf\V2V\Id\V2V_IdInterface;

class EvaluatorPartial_Id implements EvaluatorPartialInterface {

  /**
   * @var \Donquixote\Cf\Schema\Id\CfSchema_IdInterface
   */
  private $schema;

  /**
   * @var \Donquixote\Cf\V2V\Id\V2V_IdInterface
   */
  private $v2v;

  /**
   * @Cf
   *
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $schema
   *
   * @return self
   */
  public static function createFromIdSchema(CfSchema_IdInterface $schema) {
    return new self($schema, new V2V_Id_Trivial());
  }

  /**
   * @param \Donquixote\Cf\Schema\IdVal\CfSchema_IdValInterface $schema
   *
   * @return self
   */
  public static function createFromIdValSchema(CfSchema_IdValInterface $schema) {
    return new self($schema->getDecorated(), $schema);
  }

  /**
   * @param \Donquixote\Cf\Schema\Id\CfSchema_IdInterface $schema
   * @param \Donquixote\Cf\V2V\Id\V2V_IdInterface $v2v
   */
  public function __construct(CfSchema_IdInterface $schema, V2V_IdInterface $v2v) {
    $this->schema = $schema;
    $this->v2v = $v2v;
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Val\ConfToValueHelperInterface $helper
   *
   * @return mixed
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function confGetValue($conf, ConfToValueHelperInterface $helper) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return $helper->invalidConfiguration('Required id empty for id schema.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return $helper->invalidConfiguration("Unknown id '$id' for id schema.");
    }

    return $this->v2v->idGetValue($id);
  }

  /**
   * @param mixed $conf
   * @param \Donquixote\Cf\Evaluator\Helper\Php\ConfToPhpHelperInterface $helper
   *
   * @return string
   */
  public function confGetPhp($conf, ConfToPhpHelperInterface $helper) {

    if (NULL === $id = ConfUtil::confGetId($conf)) {
      return PhpUtil::incompatibleConfiguration('Required id empty for id schema.');
    }

    if (!$this->schema->idIsKnown($id)) {
      return $helper->incompatibleConfiguration($conf, "Unknown id '$id' for id schema.");
    }

    return $this->v2v->idGetPhp($id);
  }
}
