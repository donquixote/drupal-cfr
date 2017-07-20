<?php

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;

class CfSchema_DrilldownVal_Arrify extends CfSchema_DrilldownValBase {

  /**
   * @var string
   */
  private $idKey;

  /**
   * @var string
   */
  private $optionsKey;

  /**
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   * @param string $idKey
   * @param string $optionsKey
   */
  public function __construct(CfSchema_DrilldownInterface $decorated, $idKey = 'id', $optionsKey = 'options') {
    parent::__construct($decorated);
    $this->idKey = $idKey;
    $this->optionsKey = $optionsKey;
  }

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idValueGetValue($id, $value) {

    return [
      $this->idKey => $id,
      $this->optionsKey => $value,
    ];
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  final public function idPhpGetPhp($id, $php) {

    $idKeyPhp = var_export($this->idKey, TRUE);
    $optionsKeyPhp = var_export($this->optionsKey, TRUE);
    $idPhp = var_export($id, TRUE);

    return <<<EOT
[
  $idKeyPhp => $idPhp,
  $optionsKeyPhp => $php,
];
EOT;
  }
}
