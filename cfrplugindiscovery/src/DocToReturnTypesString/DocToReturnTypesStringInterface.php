<?php

namespace Drupal\cfrplugindiscovery\DocToReturnTypesString;

interface DocToReturnTypesStringInterface {

  /**
   * @param string $docComment
   *   The complete doc comment.
   *
   * @return string|null
   *   E.g. '\stdClass|null'
   */
  public function docGetReturnTypesString($docComment);

}
