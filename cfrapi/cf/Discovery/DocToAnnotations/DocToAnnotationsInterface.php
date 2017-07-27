<?php

namespace Donquixote\Cf\Discovery\DocToAnnotations;

interface DocToAnnotationsInterface {

  /**
   * @param string|null $docComment
   *
   * @return array[]
   */
  public function docGetAnnotations($docComment);

}
