<?php

namespace Donquixote\Cf\V2V\Drilldown;

use Donquixote\Cf\V2V\Id\V2V_IdInterface;

class V2V_Drilldown_FromIdV2V implements V2V_DrilldownInterface {

  /**
   * @var \Donquixote\Cf\V2V\Id\V2V_IdInterface
   */
  private $v2vId;

  /**
   * @param \Donquixote\Cf\V2V\Id\V2V_IdInterface $v2vId
   */
  public function __construct(V2V_IdInterface $v2vId) {
    $this->v2vId = $v2vId;
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
    return $this->v2vId->idGetValue($id);
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, $php) {
    return $this->v2vId->idGetPhp($id);
  }
}
