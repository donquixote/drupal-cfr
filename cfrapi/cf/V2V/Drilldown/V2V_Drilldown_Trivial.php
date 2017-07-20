<?php

namespace Donquixote\Cf\V2V\Drilldown;

class V2V_Drilldown_Trivial implements V2V_DrilldownInterface {

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idValueGetValue($id, $value) {
    return $value;
  }

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, $php) {

    // We cannot be sure if $id is multi-line.
    $idSafe = str_replace("\n", '\n', $id);

    return <<<EOT
// Drilldown with \$id = "$idSafe".
$php
EOT;
    # return $php;
  }
}
